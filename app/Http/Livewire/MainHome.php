<?php

namespace App\Http\Livewire;

use App\Models\Card;
use App\Models\StyleCard;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MainHome extends Component
{
    public $cards;
    public $background;
    public $textbackground;
    public $component_edit_text;
    public $checkmaxCards;
    public $selectTags;
    public $textbutton;
    public $backgroundbutton;
    public $backgroundMain;
    public $textbuttonMain;
    public $dateCreate;
    public $timeCreate;
    public $allstyles;
    public $backgroundscrollBar;
    public $user_id;
    public $cardadd;
    public $countMaxCardForUser = 5;
    public $countstyles;


    protected $rules = [
        'cardadd.text' => 'required|min:6',
        'cardadd.tag_id' => 'required|exists:tags,id',
        'cardadd.style_card_id' => 'required|exists:style_cards,id',
        'cardadd.source' => 'required|min:6',
        'cardadd.user_id' => 'required'
    ];

    protected $listeners = ['deletecard' => 'deletecard', 'aftercreateordelete' => 'aftercreateordelete'];

    public function mount()
    {
        $this->allstyles = StyleCard::all();
        $this->countstyles = $this->allstyles->count();
        $this->user_id = Auth::user()->id;
        $this->selectTags = Tag::all();
        $this->aftercreateordelete();
        $this->uploudall();
    }

    public function aftercreateordelete()
    {
        $this->getCards();
        $this->newCard();
        $this->resetcolor();
    }

    public function getCards()
    {
        $this->cards = Card::where('user_id', $this->user_id)->get();
    }

    public function uploudall()
    {
        $this->dateCreate = date("d.m.y");
        $this->timeCreate = date("H:i:s");
        $this->component_edit_text = 'cards.addcardShow';
    }

    public function resetcolor()
    {
        $this->colorchengfirst($this->allstyles->find($this->cardadd->style_card_id)->background,$this->allstyles->find($this->cardadd->style_card_id)->text);
        $this->colorchengmain($this->background,$this->textbackground);
        $this->colorcheng($this->background,$this->textbackground);
    }

    public function newCard()
    {
        $this->cardadd = new Card();
        $this->cardadd->fill(['tag_id' => 1]);
        $this->cardadd->fill(['user_id' => $this->user_id]);
        $this->cardadd->fill(['style_card_id' => $this->cards->last()->style_card_id ?? 1]);
    }

    public function deletecard($id)
    {
        Card::find($id)->delete();
        if ($id == $this->cards->last()->id) {
            $this->aftercreateordelete();
        } else {
            $this->getCards();
        }
    }

    public function render()
    {
        return view('livewire.main-home');
    }

    public function leftchengcolor()
    {
        $this->cardadd->style_card_id--;
        $this->cardadd->style_card_id== 0 ? $this->cardadd->style_card_id= $this->countstyles : '';
        $this->resetcolor();
    }

    public function rightchengcolor()
    {
        $this->cardadd->style_card_id== $this->countstyles ? $this->cardadd->style_card_id= 1 : $this->cardadd->style_card_id++;
        $this->resetcolor();
    }

    // Проработать двойной клик
    // Сделать валидацию на объём сымволов и другое
    // Сделать возможность добавления собственных тэгов
    // Добавить группы карточек (автоматическое объединение оных при одинаковых тегах
    // Сделать плавную анимацию перехода цветов

    public function colorchengfirst($background = '#3C3B3D', $textbackground = '#ffffff')
    {
        $this->background = $background;
        $this->textbackground = $textbackground;
    }

    public function colorchengmain($background = '#3C3B3D', $textbackground = '#ffffff')
    {
        $this->backgroundMain =  $background;
        $this->textbuttonMain =  $textbackground;
        $this->backgroundscrollBar = sscanf($textbackground, "#%02x%02x%02x");
    }

    public function colorcheng($background = '#3C3B3D', $textbackground = '#ffffff')
    {
        $this->backgroundbutton = $background;
        $this->textbutton = $textbackground;
    }

    public function clickNext1()
    {
        $this->validate([
            'cardadd.text' => 'required|min:6',
            'cardadd.tag_id' => 'required|exists:tags,id',
        ]);
        $this->colorchengmain($this->textbackground,$this->background);
        $this->colorcheng($this->background,$this->textbackground);
        $this->component_edit_text = 'cards.addcardEditSource';
    }

    public function clickBack1()
    {
        $this->colorchengmain($this->textbackground,$this->background);
        $this->colorcheng($this->background,$this->textbackground);
        $this->component_edit_text = 'cards.addcardEdit';
    }

    public function clickNext2()
    {
        $this->validate([
            'cardadd.source' => 'required|min:6'
        ]);
        $this->colorchengmain($this->background,$this->textbackground);
        $this->colorcheng($this->background,$this->textbackground);
        $this->component_edit_text = 'cards.addcardPreview';
    }

    public function clickBack2()
    {
        $this->colorchengmain($this->textbackground,$this->background);
        $this->colorcheng($this->textbackground,$this->background);
        $this->component_edit_text = 'cards.addcardEditSource';
    }

    public function clickNext3()
    {
        $this->validate([
            'cardadd.style_card_id' => 'required|exists:style_cards,id',
        ]);
        $this->maxCards();
        if ($this->cardadd->user_id == $this->user_id) {
            if (!$this->checkmaxCards) {
                $this->cardadd->save();
                $this->aftercreateordelete();
                $this->uploudall();
            }
        } else {
            $this->aftercreateordelete();
            $this->uploudall();
        }

    }

    public function maxCards()
    {
        $this->checkmaxCards = ($this->cards->count() + 1) > $this->countMaxCardForUser ? true : '';
    }

    public function click_edit()
    {
        $this->colorchengmain($this->textbackground,$this->background);
        $this->colorcheng($this->textbackground,$this->background);
        $this->component_edit_text = 'cards.addcardEdit';
    }

    public function click_chow()
    {
        $this->uploudall();
        $this->aftercreateordelete();
    }
}
