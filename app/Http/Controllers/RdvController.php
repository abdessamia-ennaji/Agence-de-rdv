<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rdv;
use Carbon\Carbon;





class RdvController extends Controller
{


        private $timeSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', 
            '12:00', '12:30', '13:00', '15:00', '15:30', '16:00', 
            '16:30', '17:00', '17:30', '18:00'
        ];



        public function createStep1()
{
    $bookedSlots = Rdv::all()
        ->groupBy(function($item) {
            return Carbon::parse($item->rdv_date)->format('Y-m-d');
        })
        ->map(function($items) {
            return $items->pluck('rdv_time')->toArray();
        })
        ->toArray();

    $availableTimeSlots = $this->timeSlots;

    return view('rdv.step1', [
        'timeSlots' => $availableTimeSlots,
        'bookedSlots' => $bookedSlots,
        'minDate' => Carbon::today()->format('Y-m-d'),
        'maxDate' => Carbon::today()->addDays(30)->format('Y-m-d')
    ]);
}
        
        
        


    // Handle submission of the first step (rdv_date and rdv_time)
    public function storeStep1(Request $request)
    {
        // Validate data
        $validatedData = $request->validate([
            'rdv_date' => 'required|date',
            'rdv_time' => 'required|date_format:H:i',
        ]);
    
        // Save data to session
        session([
            'rdv_date' => $validatedData['rdv_date'],
            'rdv_time' => $validatedData['rdv_time'],
        ]);
    
        // Redirect to the second step
        return redirect()->route('rdv.createStep2');
    }
    


    // Show the second page (prenom, nom, telephone, adresse, ville, commentaire)
        public function createStep2()
        {
            return view('rdv.step2');
        }




        // Handle submission of the second step
        public function storeStep2(Request $request)
        {
            // Validate data
            $validatedData = $request->validate([
                'prenom' => 'required|string|max:191',
                'nom' => 'required|string|max:191',
                'telephone' => 'required|string|max:15',
                'adresse' => 'required|string|max:191',
                'ville' => 'required|string|max:191',
                'commentaire' => 'nullable|string',
            ]);

            // Save data to session
            session([
                'prenom' => $validatedData['prenom'],
                'nom' => $validatedData['nom'],
                'telephone' => $validatedData['telephone'],
                'adresse' => $validatedData['adresse'],
                'ville' => $validatedData['ville'],
                'commentaire' => $validatedData['commentaire'],
            ]);

            // Redirect to the final review step
            return redirect()->route('rdv.createReview');
        }


// Show the review page (review data and submit button)
    public function createReview()
    {
        return view('rdv.review');
    }
    
 // Handle form submission (store all the data)
    public function store(Request $request)
    {
        // Retrieve the data from the session
        $rdv_date = session('rdv_date');
        $rdv_time = session('rdv_time');
        $prenom = session('prenom');
        $nom = session('nom');
        $telephone = session('telephone');
        $adresse = session('adresse');
        $ville = session('ville');
        $commentaire = session('commentaire');


        // Store the data in the database
        Rdv::create([
            'rdv_date' => $rdv_date,
            'rdv_time' => $rdv_time,
            'prenom' => $prenom,
            'nom' => $nom,
            'telephone' => $telephone,
            'adresse' => $adresse,
            'ville' => $ville,
            'commentaire' => $commentaire,
        ]);

        // Clear the session data
        session()->forget([
            'rdv_date', 'rdv_time', 'prenom', 'nom', 'telephone', 'adresse', 'ville', 'commentaire'
        ]);

        // Redirect to a confirmation or success page
        return redirect()->route('dashboard')->with('success', 'Rendez-vous créé avec succès!');
    }





    public function index(Request $request)
{
    $search = $request->input('search');

    // Apply the search query and paginate the results
    $rdvs = RDV::query()
        ->when($search, function ($query, $search) {
            return $query->where('prenom', 'like', '%' . $search . '%')
                         ->orWhere('nom', 'like', '%' . $search . '%');
        })
        ->paginate(5); // Adjust the number of results per page

    return view('rdvs.index', compact('rdvs', 'search'));
}


    
    public function show(Request $request)
    {
        $search = $request->get('search');
    
        // Retrieve all appointments with pagination and optional filtering by prenom or nom
        $rdvs = RDV::when($search, function ($query, $search) {
            return $query->where('prenom', 'like', "%$search%")
                        ->orWhere('nom', 'like', "%$search%");
        })->paginate(5);
    
        return view('show', compact('rdvs', 'search'));
    }
    



        public function edit($id)
        {
            $rdv = Rdv::findOrFail($id); // Find the appointment by its ID
            return view('edit', compact('rdv')); // Pass the appointment data to the view
        }


        public function update(Request $request, $id)
{
    // Validate the request data
    // $validatedData = $request->validate([
    //     'prenom' => 'required|string|max:191',
    //     'nom' => 'required|string|max:191',
    //     'telephone' => 'required|string|max:15',
    //     'rdv_date' => 'required|date',
    //     'rdv_time' => 'required|date_format:H:i',
    //     'adresse' => 'required|string|max:191',
    //     'ville' => 'required|string|max:191',
    //     'commentaire' => 'nullable|string',
    // ]);


    // Find the record by ID and update it
    $rdv = Rdv::findOrFail($id);
    $rdv->update($request->all());

    // Redirect to the dashboard with a success message
    return redirect()->route('dashboard')->with('success', 'Rendez-vous modifié avec succès!');
}

        


        public function destroy($id)
        {
            $rdv = Rdv::findOrFail($id);
            $rdv->delete(); // Delete the appointment

            return back()->with('success', 'Rendez-vous supprimé avec succès!');
        }



}


