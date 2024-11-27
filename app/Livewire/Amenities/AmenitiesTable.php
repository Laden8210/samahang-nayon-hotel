<?php

namespace App\Livewire\Amenities;

use App\Models\Amenities;
use Livewire\Component;

class AmenitiesTable extends Component
{
    public $search = '';
    public $name;
    public $price;

    public $selectedAmenities;
    public $updatePrice;
    public $updateName;

    public function render()
    {
        return view('livewire.amenities.amenities-table', [
            'amenities' => Amenities::search($this->search)->get()
        ]);
    }

    public function placeholder()
    {
        return view('loader');
    }

    public function updateAmenities($amenities)
    {
        $this->selectedAmenities = Amenities::find($amenities);
        $this->updateName = $this->selectedAmenities->Name;
        $this->updatePrice = $this->selectedAmenities->Price;
        $this->dispatch('open-modal', name: 'update-modal');
    }

    public function createAmenities()
    {
        $this->validate([
            'name' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255', 'unique:amenities,Name'],
            'price' => 'required|numeric'
        ]);

        $amenities = Amenities::create([
            'Name' => $this->name,
            'Price' => $this->price
        ]);

        if ($amenities) {
            $this->dispatch('close-modal');
            session()->flash('message', 'Amenities Created successfully.');
            $this->name = '';
            $this->price = '';
        }
    }


    public function update()
    {
        $this->validate([
            'updateName' => [
                'required',
                'regex:/^[a-zA-Z\s]+$/',
                'max:255',
                'unique:amenities,Name,' . $this->selectedAmenities->AmenitiesId
            ],
            'updatePrice' => 'required|numeric'
        ]);

        $this->selectedAmenities->update([
            'Name' => $this->updateName,
            'Price' => $this->updatePrice
        ]);

        $this->updateName = '';
        $this->updatePrice = '';
        $this->dispatch('close-modal');
        session()->flash('message', 'Amenity updated successfully.');
    }


    public function setAmenitiesId($amenities)
    {

        $this->selectedAmenities = Amenities::find($amenities);
        $this->dispatch('open-modal', name: 'delete-modal');
    }

    public function delete()
    {
        $this->selectedAmenities->delete();
        session()->flash('message', 'Amenity deleted successfully.');
        $this->dispatch('close-modal');
    }
}
