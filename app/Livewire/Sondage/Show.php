<?php

namespace App\Livewire\Sondage;

use App\Models\Poll;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{

    public Poll $sondage;
    public $question;
    public $description;
    public $accessibilite;
    public $date_fin;
    public $options = [];
    public $participant;
    public $filiere;

    public null | Vote $myVote = null;
    public $isFinish = false;

    public function vote($index)
    {
        if ($this->isFinish) return;

        if (!$this->myVote) {
            $this->options[$index]['votes']++;
            $this->sondage->update(['options' => json_encode($this->options)]);

            $vote = new Vote();
            $vote->option_choisi = $this->options[$index]['option'];
            $vote->user_id = Auth::user()->id;
            $vote->poll_id = $this->sondage->id;
            $vote->save();
            return redirect()->route("sondages.show", $this->sondage->id)->with('success', 'Votre vote a été pris en compte');
        }
    }

    public function removeVote($index)
    {
        if ($this->isFinish) return;

        if ($this->options[$index]['votes'] > 0 && $this->myVote) {
            $this->myVote->delete();
            $this->options[$index]['votes']--;
            $this->sondage->update(['options' => json_encode($this->options)]);
            return redirect()->route("sondages.show", $this->sondage->id)->with('success', 'Votre vote a été retiré avec succès');
        }
    }


    public function mount(Poll $sondage)
    {
        $this->sondage = $sondage;
        $this->question = $this->sondage->question;
        $this->description = $this->sondage->description;
        $this->accessibilite = $this->sondage->accessibilite;
        $this->participant = $this->sondage->participants;
        $this->filiere = $this->sondage->filiere->nom;
        $this->date_fin = $this->sondage->date_fin;
        $this->options = json_decode($this->sondage->options, true);


        $date_fin = Carbon::createFromFormat('Y-m-d', $this->date_fin);
        if ($date_fin->lessThan(Carbon::today())) {
            $this->isFinish = true;
        }

        $this->myVote = Vote::query()->where(['user_id' => Auth::user()->id, 'poll_id' => $this->sondage->id])->get()->first();
    }

    public function deleteSondage($id)
    {
        $sondage = Poll::query()->findOrFail($id);
        $sondage->delete();
        redirect()->route('sondages.index');
    }


    public function render()
    {
        return view('livewire.sondage.show');
    }
}
