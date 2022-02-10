<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Drivers\Time;
use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Models\Complex;
use App\Models\Property;
use App\Models\Specification;
use App\Http\Requests\DoneeRequest;

class PropertyController extends Controller
{
  public function index(Request $request)
  {
    $model = Property::published()->orderByDesc('created_at')->paginate(50);
    $model->appends($request->except('page'));
    return view('panel.admin.properties.index', compact('model'));
  }
  public function create()
  {
    $states = State::all();
    $cities = City::all();
    $areas = Area::all();
    $complexes = Complex::all();
    return view('panel.admin.properties.create', compact('states', 'cities', 'areas', 'complexes'));
  }
  public function store(Request $request)
  {

    $this->validate($request, [
      'landlord_first_name' => 'required',
      'landlord_last_name' => 'required',
      'state_id' => 'required',
      'city_id' => 'required',
      'area_id' => 'required',
      'complex_id' => 'required',
      'type' => 'required',
      'water' => 'required',
      'electricity' => 'required',
      'gas' => 'required',
    ]);
    $property = Property::create([
      'type' => $request->type ?? 1,
      'landlord_first_name' => $request->landlord_first_name,
      'landlord_last_name' => $request->landlord_last_name,
      'primary_mobile' => $request->primary_mobile ?? null,
      'secondary_mobile' => $request->secondary_mobile ?? null,
      'phone' => $request->phone ?? null,
      'address' => $request->address ?? null,
      'description' => $request->description ?? null,
      'deed' => $request->deed ?? Property::SANAD,
      'usage' => $request->usage ?? Property::RESIDENTIAL,
      'for_rent' => ($request->for_rent && $request->for_rent == "on") ? true : false,
      'for_sell' => ($request->for_sell && $request->for_sell == "on") ? true : false,
      'for_pre_sell' => ($request->for_pre_sell && $request->for_pre_sell == "on") ? true : false,
      'parking' => ($request->parking && $request->parking == "on") ? true : false,
      'storage' => ($request->storage && $request->storage == "on") ? true : false,
      'elevator' => ($request->elevator && $request->elevator == "on") ? true : false,
      'balcony' => ($request->balcony && $request->balcony == "on") ? true : false,
      'yard' => ($request->yard && $request->yard == "on") ? true : false,
      'share' => $request->share ?? 6,
      'floor' => $request->floor ?? 0,
      'total_floor' => $request->total_floor ?? null,
      'unit' => $request->unit ?? 0,
      'total_unit' => $request->total_unit ?? null,
      'total_area' => $request->total_area ?? null,
      'built_area' => $request->built_area ?? null,
      'age' => $request->age ?? null,
      'total_rooms' => $request->total_rooms ?? 0,
      'toilet_together' => $request->toilet_together ?? false,
      'state_id' => $request->state_id ?? null,
      'city_id' => $request->city_id ?? null,
      'area_id' => $request->area_id ?? null,
      'complex_id' => $request->complex_id && $request->complex_id != 'no' ? $request->complex_id : null,
    ]);

    $property->refresh();

    Specification::create([
      'property_id' => $property->id,
      'total_price' => $request->total_price ?? null,
      'unit_price'  => $request->unit_price ?? null,
      'deposit' => $request->deposit ?? null,
      'rent'  => $request->rent ?? null,
      'is_empty'  => $request->is_empty ?? 0,
      'sold'  => ($request->rent && $request->rent == "on") ? true : false,
      'rented'  => ($request->rented && $request->rented == "on") ? true : false,
      'exchangeable'  => $request->exchangeable ?? 0,
      'flexible'  => $request->flexible ?? 0,
      'cabinet' => ($request->cabinet && $request->cabinet == "on") ? true : false,
      'parket'  => ($request->parket && $request->parket == "on") ? true : false,
      'heating' => $request->heating ?? 0,
      'cooling' => ($request->cooling && $request->cooling == "on") ? true : false,
      'telephone' => ($request->telephone && $request->telephone == "on") ? true : false,
      'water' => $request->water ?? 0,
      'electricity' => $request->electricity ?? 0,
      'gas' => $request->gas ?? 0,
      'evacuation_date' =>  $request->evacuation_date ? (\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $request->evacuation_date)->format('Y-m-d H:i:s')) : null
    ]);

    return redirect()->route('properties.index');
  }
  public function edit($property)
  {
    $model = Property::find($property);
    $states = State::all();
    $cities = City::all();
    $areas = Area::all();
    $complexes = Complex::all();
    return view('panel.admin.properties.edit', compact('model', 'states', 'cities', 'areas', 'complexes'));
  }
  public function update(Request $request, $property)
  {
    $this->validate($request, [
      'landlord_first_name' => 'required',
      'landlord_last_name' => 'required',
      'state_id' => 'required',
      'city_id' => 'required',
      'area_id' => 'required',
      'complex_id' => 'required',
      'type' => 'required',
      'water' => 'required',
      'electricity' => 'required',
      'gas' => 'required',
    ]);

    Property::findOrFail($property)->update([
      'type' => $request->type ?? 1,
      'landlord_first_name' => $request->landlord_first_name,
      'landlord_last_name' => $request->landlord_last_name,
      'primary_mobile' => $request->primary_mobile ?? null,
      'secondary_mobile' => $request->secondary_mobile ?? null,
      'phone' => $request->phone ?? null,
      'address' => $request->address ?? null,
      'description' => $request->description ?? null,
      'deed' => $request->deed ?? Property::SANAD,
      'usage' => $request->usage ?? Property::RESIDENTIAL,
      'for_rent' => ($request->for_rent && $request->for_rent == "on") ? true : false,
      'for_sell' => ($request->for_sell && $request->for_sell == "on") ? true : false,
      'for_pre_sell' => ($request->for_pre_sell && $request->for_pre_sell == "on") ? true : false,
      'parking' => ($request->parking && $request->parking == "on") ? true : false,
      'storage' => ($request->storage && $request->storage == "on") ? true : false,
      'elevator' => ($request->elevator && $request->elevator == "on") ? true : false,
      'balcony' => ($request->balcony && $request->balcony == "on") ? true : false,
      'yard' => ($request->yard && $request->yard == "on") ? true : false,
      'share' => $request->share ?? 6,
      'floor' => $request->floor ?? 0,
      'total_floor' => $request->total_floor ?? null,
      'unit' => $request->unit ?? 0,
      'total_unit' => $request->total_unit ?? null,
      'total_area' => $request->total_area ?? null,
      'built_area' => $request->built_area ?? null,
      'age' => $request->age ?? null,
      'total_rooms' => $request->total_rooms ?? 0,
      'toilet_together' => $request->toilet_together ?? false,
      'state_id' => $request->state_id ?? null,
      'city_id' => $request->city_id ?? null,
      'area_id' => $request->area_id ?? null,
      'complex_id' => $request->complex_id && $request->complex_id != 'no' ? $request->complex_id : null,
    ]);


    Specification::where('property_id', $property)->update([
      'total_price' => $request->total_price ?? null,
      'unit_price'  => $request->unit_price ?? null,
      'deposit' => $request->deposit ?? null,
      'rent'  => $request->rent ?? null,
      'is_empty'  => $request->is_empty ?? 0,
      'sold'  => ($request->rent && $request->rent == "on") ? true : false,
      'rented'  => ($request->rented && $request->rented == "on") ? true : false,
      'exchangeable'  => $request->exchangeable ?? 0,
      'flexible'  => $request->flexible ?? 0,
      'cabinet' => ($request->cabinet && $request->cabinet == "on") ? true : false,
      'parket'  => ($request->parket && $request->parket == "on") ? true : false,
      'heating' => $request->heating ?? 0,
      'cooling' => ($request->cooling && $request->cooling == "on") ? true : false,
      'telephone' => ($request->telephone && $request->telephone == "on") ? true : false,
      'water' => $request->water ?? 0,
      'electricity' => $request->electricity ?? 0,
      'gas' => $request->gas ?? 0,
      'evacuation_date' =>  $request->evacuation_date ? (\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $request->evacuation_date)->format('Y-m-d H:i:s')) : null
    ]);

    return redirect()->route('properties.index');
  }

  public function archive(Request $request)
  {

    Property::where('id', $request->id)->update([
      'status' => Property::ARCHIVED
    ]);

    return redirect()->route('properties.index');
  }

  public function archived(Request $request)
  {
    $model = Property::archived()->orderByDesc('created_at')->paginate(50);
    $model->appends($request->except('page'));
    return view('panel.admin.archived.properties', compact('model'));
  }

  public function publish(Request $request)
  {

    Property::where('id', $request->id)->update([
      'status' => Property::PUBLISHED
    ]);

    return redirect()->route('archived.properties');
  }
}
