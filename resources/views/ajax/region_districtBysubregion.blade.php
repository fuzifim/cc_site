<option value="0">Lựa chọn</option>
@foreach($data_district as $data_item)
    <option value="{{$data_item->id}}">{{$data_item->district_name}}</option>
@endforeach