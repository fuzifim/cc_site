<option value="0">Lựa chọn</option>
@foreach($data_ward as $data_item)
    <option value="{{$data_item->id}}">{{$data_item->ward_name}}</option>
@endforeach