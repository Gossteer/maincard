<div class="multi-button"  style="{{$attributes['style2']}}">
    <button class="fas fa-heart" wire:click="clickNext2()"></button>
    <button class="fas fa-heart" wire:click="clickBack1"></button>
    <button class="fas fa-heart" wire:click="click_chow()"></button>
</div>
<div class="containerEdit"  style="{{$attributes['style1']}}">
    <div class="mainText" style=" height: 94%;" >
        <textarea wire:model.defer="cardadd.source" maxlength="255" style="{{$attributes['backgroundscrollBar']}}"  title=" @error('cardadd.source') {{$message}} @enderror " class="texForTexarea @error('card.source') {{"error"}} @enderror "></textarea>
    </div>
</div>
