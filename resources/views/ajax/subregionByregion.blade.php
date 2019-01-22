<option value="0">Lựa chọn</option>
@foreach($data_subregion as $data_item)
    <option value="{{$data_item->id}}">{{$data_item->subregions_name}}</option>
@endforeach