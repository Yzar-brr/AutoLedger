<?php

namespace App\Livewire;

use App\Models\Intervention;
use App\Models\Truck;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Laravel\Facades\Image;

class TruckInterventions extends Component
{
    use WithFileUploads;

    public Truck $truck;

    public $date;

    public $technician;

    public $price;

    public $mileage;

    public $duration;

    public $description;

    public $newPhotos = [];

    public $maintenance = [];

    public function mount($truck)
    {
        $this->truck = $truck;
        $this->date = now()->format('Y-m-d'); // Date d'aujourd'hui par défaut
        $defaultKeys = [
            'pneus_av',
            'pneus_ar',
            'plaquettes_av',
            'plaquettes_ar',
            'disques_av',
            'disques_ar',
            'frein_a_main',
            'niveau_lr',
            'niveau_huile',
            'niveau_liquide_frein',
            'essuie_glace_av',
            'eclairage_av',
            'eclairage_ar'
        ];
        foreach ($defaultKeys as $key) {
            $this->maintenance[$key] = '';
        }
        $lastPlan = \App\Models\MaintenancePlan::where('truck_id', $this->truck->id)->latest()->first();
        if ($lastPlan) {
            $this->maintenance = array_merge($this->maintenance, $lastPlan->data);
        }
    }

    public function addIntervention()
    {
        $this->validate([
            'date' => 'required|date',
            'technician' => 'required|string',
            'price' => 'required|numeric',
            'mileage' => 'nullable|integer',
            'duration' => 'required|string',
            'description' => 'required|string',
            'newPhotos.*' => 'image|max:20000',
        ]);

        // Gestion des images
        $photoPaths = [];
        foreach ($this->newPhotos as $photo) {
            $filename = hexdec(uniqid()) . '.' . $photo->getClientOriginalExtension();
            $directory = 'interventions/' . $this->truck->id;
            $fullPath = storage_path('app/public/' . $directory . '/' . $filename);
            if (!file_exists(storage_path('app/public/' . $directory))) {
                mkdir(storage_path('app/public/' . $directory), 0755, true);
            }
            $img = Image::read($photo->getRealPath());
            $img->scale(width: 1200);
            $img->save($fullPath, quality: 75);

            $photoPaths[] = $directory . '/' . $filename;
        }

        $this->truck->interventions()->create([
            'date' => $this->date,
            'technician' => $this->technician,
            'price' => $this->price,
            'mileage' => $this->mileage,
            'duration' => $this->duration,
            'description' => $this->description,
            'photos' => $photoPaths,
        ]);

        $this->reset(['technician', 'price', 'mileage', 'duration', 'description', 'newPhotos']);
        session()->flash('message', 'Intervention ajoutée !');
    }

    public function deleteIntervention($id)
    {
        Intervention::findOrFail($id)->delete();
    }

    public function saveMaintenancePlan()
    {
        if (empty($this->maintenance)) {
            session()->flash('error', 'Veuillez remplir au moins un élément.');
            return;
        }
        \App\Models\MaintenancePlan::create([
            'truck_id' => $this->truck->id,
            'user_id' => auth()->id(), 
            'check_date' => now(),     
            'data' => $this->maintenance, 
        ]);
        session()->flash('message', 'Plan d\'entretien enregistré avec succès !');
    }

    public function render()
    {
        return view('livewire.truck-interventions', [
            'interventions' => $this->truck->interventions()->latest()->get(),
            'lastMaintenancePlan' => $this->truck->maintenancePlans()->latest()->first(),
            'maintenancePlans' => $this->truck->maintenancePlans()->with('user')->latest()->get(),
        ]);
    }
}
