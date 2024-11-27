<?php

namespace App\Livewire\Promotion;

use App\Livewire\Room\UpdateRoom;
use App\Models\DiscountedRoom;
use Livewire\Component;
use App\Models\Promotion;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class PromotionTable extends Component
{

    public $search;
    public $promotionName;
    public $description;
    public $discount;
    public $startingDate;
    public $endDate;
    public $selectedPromotion;
    public $selectRoom  = [];

    public $updateSelectRoom = [];
    public $updateRoom;

    public $discountedrooms;

    public function render()
    {
        $rooms = Room::select('RoomType', DB::raw('COUNT(*) as total_rooms'))
            ->groupBy('RoomType')
            ->get();
        return view(
            'livewire.promotion.promotion-table',
            [
                'promotions' => Promotion::search($this->search)->get(),
                'rooms' => $rooms,
            ]
        );
    }
    public function addPromotion()
    {
        $this->validate([
            'promotionName' => 'required|unique:promotions,Promotion',
            'description' => 'required',
            'discount' => 'required|numeric|max:50',
            'startingDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after_or_equal:startingDate',
        ], [
            'promotionName.required' => 'The promotion name is required.',
            'promotionName.unique' => 'The promotion name must be unique.',
            'description.required' => 'The description is required.',
            'discount.required' => 'The discount is required.',
            'discount.numeric' => 'The discount must be a numeric value.',
            'discount.max' => 'The maximum discount is 50%.',  // Custom error message
            'startingDate.required' => 'The starting date is required.',
            'startingDate.date' => 'The starting date must be a valid date.',
            'startingDate.after_or_equal' => 'The starting date cannot be in the past.',
            'endDate.required' => 'The end date is required.',
            'endDate.date' => 'The end date must be a valid date.',
            'endDate.after_or_equal' => 'The end date must be after or equal to the starting date.',
        ]);

        // Check for overlapping promotions
        $overlappingPromotion = Promotion::where(function ($query) {
            $query->whereBetween('StartDate', [$this->startingDate, $this->endDate])
                ->orWhereBetween('EndDate', [$this->startingDate, $this->endDate]);
        })->first();

        if ($overlappingPromotion) {
            session()->flash('message', 'There is already a promotion that overlaps with the selected dates.');
            return;
        }

        $promotion = new Promotion();
        $promotion->Promotion = $this->promotionName;
        $promotion->Description = $this->description;
        $promotion->Discount = $this->discount;
        $promotion->StartDate = $this->startingDate;
        $promotion->EndDate = $this->endDate;
        $promotion->DateCreated = now();
        $promotion->save();

        $rooms = Room::whereIn('RoomType', $this->selectRoom)->get();

        foreach ($rooms as $room) {
            $promotion->discountedRooms()->create([
                'RoomId' => $room->RoomId,
            ]);
        }

        session()->flash('message', 'Promotion added successfully.');
    }


    public function updatePromotion($promotionId)
    {
        $this->selectedPromotion = Promotion::find($promotionId);

        if (!$this->selectedPromotion) {
            session()->flash('error', 'Promotion not found.');
            return;
        }

        $this->promotionName = $this->selectedPromotion->Promotion;
        $this->description = $this->selectedPromotion->Description;
        $this->discount = $this->selectedPromotion->Discount;
        $this->startingDate = $this->selectedPromotion->StartDate;
        $this->endDate = $this->selectedPromotion->EndDate;

        $this->updateRoom = DiscountedRoom::with('room')
            ->where('PromotionId', $this->selectedPromotion->PromotionId)
            ->get();


        $this->discountedrooms = Room::select('RoomType', DB::raw('COUNT(*) as total_rooms'))
            ->groupBy('RoomType')
            ->get();


        $this->discountedrooms = $this->discountedrooms->map(function ($room) {
            $room->isChecked = false;


            if ($this->updateRoom) {
                foreach ($this->updateRoom as $value) {
                    if ($value->room && $value->room->RoomType == $room->RoomType) {
                        $room->isChecked = true;
                        break;
                    }
                }
            }

            return $room;
        });

        $this->updateSelectRoom = $this->discountedrooms->where('isChecked', true)->pluck('RoomType')->toArray();
    }


    public function savePromotion()
    {
        $this->validate([
            'promotionName' => 'required',
            'description' => 'required',
            'discount' => 'required|numeric|max:50',
            'startingDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after_or_equal:startingDate',
        ], [
            'promotionName.required' => 'The promotion name is required.',
            'description.required' => 'The description is required.',
            'discount.required' => 'The discount is required.',
            'discount.max' => 'The maximum discount is 50%.',
            'startingDate.required' => 'The starting date is required.',
            'startingDate.after_or_equal' => 'The starting date cannot be in the past.',
            'startingDate.date' => 'The starting date must be a valid date.',
            'endDate.required' => 'The end date is required.',
            'endDate.date' => 'The end date must be a valid date.',
            'endDate.after_or_equal' => 'The end date must be after or equal to the starting date.',
        ]);

        // Update the selected promotion
        $this->selectedPromotion->update([
            'Promotion' => $this->promotionName,
            'Description' => $this->description,
            'Discount' => $this->discount,
            'StartDate' => $this->startingDate,
            'EndDate' => $this->endDate,
        ]);

        $selectedRoomIds = [];

        foreach ($this->updateSelectRoom as $s) {

            $rooms = Room::where('RoomType', $s)->get();

            foreach ($rooms as $room) {
                $selectedRoomIds[] = $room->RoomId;

                $roomExist = DiscountedRoom::where('RoomId', $room->RoomId)
                    ->where('PromotionId', $this->selectedPromotion->PromotionId)
                    ->first();

                if (!$roomExist) {
                    $this->selectedPromotion->discountedRooms()->create([
                        'RoomId' => $room->RoomId,
                    ]);
                }
            }
        }

        DiscountedRoom::where('PromotionId', $this->selectedPromotion->PromotionId)
            ->whereNotIn('RoomId', $selectedRoomIds)
            ->delete();

        $this->dispatch('close-modal', name: 'update-modal');
        $this->reset();

        session()->flash('message', 'Promotion updated successfully.');
    }


    public function deletePromotion($promotionId)
    {
        Promotion::destroy($promotionId);
        session()->flash('message', 'Promotion deleted successfully.');
    }
}
