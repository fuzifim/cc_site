<div class="form-group">
	<label class="control-label" for="phone">Mã số thuế </label>
	<input type="text" ng-model="[]" autocomplete="on" id="taxcode_company_primary" class="form-control" name="taxcode_company_cp" value="" placeholder="Nhập mã số thuế"/>
</div>
<div class="form-group">
   <label class="control-label" for="phone">Tên doanh nghiệp <span class="text-danger">(*)</span></label>
	<input id="id_ps_auto"  name="id_company" value="{{ $temp_set->template_setting_company }}" type="hidden" class="form-control">
	<input id="name_ps_auto" placeholder="Công ty TNHH Đại Thế Giới" name="title_shop" value="{{ $temp_set->title_shop }}" type="text" class="form-control">
</div>
<div class="form-group">
    <label class="control-label" for="phone">Số điện thoại <span class="text-danger">(*)</span></label>
	<input id="phone_ps_auto" placeholder="0988.888.888" data-toggle="tooltip" data-placement="top" title="Nhập số điện thoại" name="phone_contact" value="{{ $temp_set->phone_contact }}" type="text" class="form-control">
</div>
<div class="form-group">
	<label class="control-label pd-r-5" for="phone">Địa chỉ <span class="text-danger">(*)</span></label>
	<div class="col-title-p">
	    <div class="row">
			<div class="col-sm-4">
				<p class="control-left-p">Đường</p>
			    <input data-tooltip="Nhập địa chỉ của bạn" id="shop-address-full" type="text" name="shop_address" value="{{ $temp_set->address_shop }}" class="form-control ">
			</div>
			<div class="col-sm-4">
				<p class="control-left-p">Quốc Gia <span class="text-danger">(*)</span></p>
				<select name="region_select" id="regoin_profile" class="form-control">
					<option value="0">Lựa chọn</option>
					@if(isset($temp_set->regionsID) && $temp_set->regionsID >0)
						{!!WebService::getRegionByCode($temp_set->regionsID,'byId')!!}
					 @else
						{!!WebService::getRegionByCode()!!}
					@endif
			    </select>
			</div>
			<div class="col-sm-4">
				<p class="control-left-p">Tỉnh/Thành Phố <span class="text-danger">(*)</span></p>
				<select  name="subregion_select" id="subregoin_profile" class="form-control">
					@if(isset($temp_set->subRegionsID) && $temp_set->subRegionsID >0)
						{!!WebService::getSubregionbyID($temp_set->subRegionsID)!!}
					@else
						{!!WebService::getSubregionbyID()!!}
					@endif
				</select>
			</div>
	   </div>
	   <div class="row">
		   <div class="col-sm-4">
			   <p class="control-left-p">Quận/Huyện</p>
				<select  name="district_select" id="district_profile" class="form-control">
					<option value="">Lựa chọn</option>
					@if(isset($temp_set->districtRegionsID) && $temp_set->districtRegionsID >0)
						@if(isset($temp_set->subRegionsID) && $temp_set->subRegionsID >0)
							{!!WebService::getRegionDistrictbyID($temp_set->subRegionsID,$temp_set->districtRegionsID)!!}
						@else
							{!!WebService::getRegionDistrictbyID(0,$temp_set->districtRegionsID)!!}
						@endif 
					 @else
						@if(isset($temp_set->subRegionsID) && $temp_set->subRegionsID >0)
							{!!WebService::getRegionDistrictbyID($temp_set->subRegionsID)!!}
						@else
							{!!WebService::getRegionDistrictbyID()!!}
						@endif
					@endif
				</select>
		   </div>

		   <div class="col-sm-8">
			   <p class="control-left-p">Phường/Xã</p>
			   <select  name="ward_select" id="ward_profile" class="form-control">
					<option value="">Lựa chọn</option>
					@if(isset($temp_set->wardRegionsID) && $temp_set->wardRegionsID >0)
						@if(isset($temp_set->districtRegionsID) && $temp_set->districtRegionsID >0)
							{!!WebService::getRegionWardbyID($temp_set->regionsID,$temp_set->subRegionsID,$temp_set->districtRegionsID,$temp_set->wardRegionsID)!!}
						@else
							{!!WebService::getRegionWardbyID($temp_set->regionsID,$temp_set->subRegionsID,0,$temp_set->wardRegionsID)!!}
						@endif 
					@else
						@if(isset($temp_set->districtRegionsID) && $temp_set->districtRegionsID >0)
							{!!WebService::getRegionWardbyID($temp_set->districtRegionsID)!!}
						@else 
							{!!WebService::getRegionWardbyID()!!} 
						@endif
					@endif
			   </select>
		   </div>
	   </div>
	</div>
</div>