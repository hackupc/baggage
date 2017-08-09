@extends('layouts.app')

@section('content')
<div id="main-wrap">
  <div id="user-wrap">
    <div class="user-tab">
      <a class="user-tabs {{ $tabs[0] }}" href="/create">Check-in</a>
      <a class="user-tabs {{ $tabs[1] }}" href="/list">List</a>
      <a class="user-tabs {{ $tabs[2] }}" href="/history">History</a>
      <a class="tabs-last user-tabs {{ $tabs[3] }}" href="/search">Search</a>
    </div>
    @if($tabs2[0]==true)
      <div id="user-checkin" class="user-content">
        <form method="post" id="reg_form" action="" onsubmit="return verifyForm();">
          <div>
            <h2 class="user-title">Baggage check-in</h2>
            @if(!empty($newposition))
              <p class="user-title">Position selected: {{ $newposition[0] }}{{ $newposition[1] }} (<a href="./">Remove</a>)</p>
            @endif
          </div>
          <div>
            <input type="text" id="reg_id" name="reg_id" placeholder="ID/Passport">
            <input type="text" id="reg_name" name="reg_name" placeholder="Name">
            <input type="text" id="reg_surname" name="reg_surname" placeholder="Surname">
            <input type="text" id="reg_desc" name="reg_desc" placeholder="Description">
            @if(!empty($newposition))
                <input type="hidden" id="reg_row" name="reg_row" value="{{ $newposition[0] }}">
                <input type="hidden" id="reg_col" name="reg_col" value="{{ $newposition[1] }}">
            @else
                <input type="checkbox" id="reg_spe" name="reg_spe" value="Special">Special<br>
            @endif
            <input type="submit" id="reg_submit" name="reg_submit" value="Submit">
          </div>
        </form>
      </div>
    @endif
    @if($tabs2[1]==true)
      <div id="user-list" class="user-content">
        <div>
          <h2 class="user-title">Baggage list</h2>
        </div>
        @foreach($lists as $list)
        <div class="list">
          <div class="list-left">
            <a href="/list/{{ $list['row'] }}{{ $list['col'] }}">{{ $list['row'] }}{{ $list['col'] }}</a>
            <p>{{ date("j/Y g:i\h", strtotime($list['created'])) }}</p>
          </div>
          <div class="list-right">
            <a href="/user/{{ $list['id'] }}">{{ $list['id'] }}</a>
            <p>{{ $list['name'] }} {{ $list['surname'] }}</p>
          </div>
        </div>
        @endforeach
      </div>
    @endif
    @if($tabs2[2]==true)
      <div id="user-list" class="user-content">
        <div>
          <h2 class="user-title">Baggage history</h2>
        </div>
        @foreach($lists as $list)
        <div class="list">
          <div class="list-left">
            <a href="/list/{{ $list['row'] }}{{ $list['col'] }}">{{ $list['row'] }}{{ $list['col'] }}</a>
            <p>{{ date("j/y g:i\h", strtotime($list['created'])) }} - {{ date("j/y g:i\h", strtotime($list['deleted'])) }}</p>
          </div>
          <div class="list-right">
            <a href="/user/{{ $list['id'] }}">{{ $list['id'] }}</a>
            <p>{{ $list['name'] }} {{ $list['surname'] }}</p>
          </div>
        </div>
        @endforeach
      </div>
    @endif
  </div>
  <div id="pos-wrap">
    <table>
      @foreach($baggages as $baggage_row)
        <tr>
          @foreach($baggage_row as $baggage)
            @if($baggage[1]==$med_col)
              <td id="hupc-pos_{{ $baggage[0] }}-corridor" class="pos-med">
              </td>
            @endif
            @if($baggage[2])
              <td id="hupc-pos_{{ $baggage[0] }}{{ $baggage[1] }}" style="background-color: #E71754;">
                <a class="occupied" href="/current/{{ $baggage[0] }}{{ $baggage[1] }}">{{ $baggage[0] }}{{ $baggage[1] }}</a>
              </td>
            @else
              <td id="hupc-pos_{{ $baggage[0] }}{{ $baggage[1] }}">
                <a class="free" href="/create/{{ $baggage[0] }}{{ $baggage[1] }}">{{ $baggage[0] }}{{ $baggage[1] }}</a>
              </td>
            @endif
          @endforeach
        </tr>
      @endforeach
    </table>
  </div>
</div>
@endsection
