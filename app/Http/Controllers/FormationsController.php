<?php

namespace App\Http\Controllers;

use App\Mail\InscriptionConfirmationMail;
use App\Mail\InscriptionTableConversationMail;
use App\Models\Calendar;
use App\Models\Company;
use App\Models\Formations;
use App\Models\InscriptionFormation;
use App\Models\InscriptionTableConversation;
use Illuminate\Http\Request;
use App\Models\TableConversation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class FormationsController extends Controller
{
    public function index(){

        $formations = Formations::all();
        return view('formations.index', compact('formations'));
    }

    public function formations($slug){

            $formation = Formations::where('slug', $slug)->first();

            return view('formations.formation', compact('slug','formation'));
    }

    public function inscrits(Request $request, $id)
    {

        $calendar = Calendar::where('id', $id)->first();



        if (!$calendar) {
            return redirect()->back()->with('info', 'Calendrier non trouvé.');
        }

        $formation = Formations::find($calendar->formations_id);

        $user = auth()->user();

        if (!$user) {
            // Validate the request data
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name'  => 'required|string|max:255',
                'email'      => 'required|email|max:255',
                'phone'      => 'required|string|max:20',
            ], [
                'first_name.required' => 'Le champ prénom est obligatoire.',
                'last_name.required'  => 'Le champ nom de famille est obligatoire.',
                'email.required'      => 'Le champ adresse e-mail est obligatoire.',
                'phone.required'      => 'Le champ numéro de téléphone est obligatoire.',
            ]);

            // Find user by email
            $user = \App\Models\User::where('email', $request->email)->first();

            if (!$user) {
                // Craate a new user if not found
                $user = \App\Models\User::create([
                    'name'       => $request->first_name . ' ' . $request->last_name,
                    'email'      => $request->email,
                    'telephone'      => $request->phone,
                    'password'    => bcrypt(Str::random(12)),
                    'role'        => 'etudiant', // Asignar rol guest por defecto
                    'address'     =>  null,
                    'postal_code' => null,
                    'province'    => null,
                    'region'      => null,
                    'society'     => null,
                    'is_active'   => 0, // Inactivo hasta que verifique su email

                ]);


                Password::sendResetLink(['email' => $user->email]);


            }
        }


        // Verificar si ya está inscrito
        $alreadyInscribed = InscriptionFormation::where('user_id', $user->id)
            ->where('formations_id', $calendar->formations_id)
            ->where('levels_id', $calendar->levels_id)
            ->exists();

        if ($alreadyInscribed) {
            return redirect()->back()->with('info', 'Vous êtes déjà inscrit à cette formation.');
        }

        // Crear inscripción
        $inscription = InscriptionFormation::create([
            'reduit_rate'    => $request->reduit_rate ? 1 : 0,
            'formations_id'  => $calendar->formations_id,
            'levels_id'      => $calendar->levels_id,
            'calendar_id'    => $calendar->id,
            'user_id'        => $user->id,
            'status'         => 0, // Par défaut, le statut est 0 (non confirmé)
        ]);

        // Enviar correo de confirmación
        Mail::to($user->email)
            ->cc(config('mail.from.address'))
            ->send(new InscriptionConfirmationMail($inscription, $formation, $calendar));

        return redirect()->back()->with('success', 'Inscription réussie !');
    }



    public function inscription($slug, $id){


        $inscription = TableConversation::FindOrFail($id);
        $availables =  TableConversation::where('status',1)->get();
        $company = Company::first();
        return view('formations.inscription.tableconversation', compact('slug','inscription','company','availables'));
    }

    public function tabledeconversation(Request $request)
    {
        $request->validate([
            'tableconversation_id' => 'required|exists:table_conversations,id',
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:20',
        ], [
            'first_name.required' => 'Le champ prénom est obligatoire.',
            'last_name.required'  => 'Le champ nom de famille est obligatoire.',
            'email.required'      => 'Le champ adresse e-mail est obligatoire.',
            'phone.required'      => 'Le champ numéro de téléphone est obligatoire.',
        ]);

        $tableconvertation = TableConversation::find((int)$request->tableconversation_id);

        $qty = $tableconvertation->inscription - 1;


        if ($tableconvertation->inscription > 0) {
            $tableconvertation->decrement('inscription');
        }

        if ($tableconvertation->inscription == 0) {
            $tableconvertation->update(['open' => 0]);
        }


        $jours = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        $mois = [
            1 => 'janvier',
            2 => 'février',
            3 => 'mars',
            4 => 'avril',
            5 => 'mai',
            6 => 'juin',
            7 => 'juillet',
            8 => 'août',
            9 => 'septembre',
            10 => 'octobre',
            11 => 'novembre',
            12 => 'décembre',
        ];

        // Vérifier si la table de conversation existe
        if (!$tableconvertation) {
            return redirect()->back()->with('error', 'Table de conversation non trouvée.');
        }

        // Verifier si email existe déjà
        $existingInscription = InscriptionTableConversation::where('email', $request->email)
            ->where('tableconversation_id', $request->tableconversation_id)
            ->first();
        if ($existingInscription) {
            return redirect()->back()->with('info', 'Vous êtes déjà inscrit à cette table de conversation.');
        }

        $date = \Carbon\Carbon::parse($tableconvertation->date_start);
        $jourSemaine = $jours[$date->dayOfWeek];
        $moisFr = $mois[$date->month];

        $dateFr = $jourSemaine . ' ' . $date->format('j') . ' ' . $moisFr . ' ' . $date->format('Y');
        $heure = $tableconvertation->hour_start . ' à ' . $tableconvertation->hour_end;

        // Pour tester :
       // dd(['date' => $dateFr, 'heure' => $heure]);
        // Créer l'inscription à la table de conversation
        $inscription = InscriptionTableConversation::create([
            'tableconversation_id' => $request->tableconversation_id,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'inscription_message'  => $dateFr .' à ' . $heure,
            'status'     => 0, // Par défaut, le statut est 0 (non confirmé)
        ]);
        // Guardar o enviar email aquí...
        Mail::to($request->email)
            ->cc(config('mail.from.address'))
            ->send(new InscriptionTableConversationMail($inscription, $tableconvertation));


        return redirect()->back()->with('success', 'Inscription à la table de conversation réussie !');
    }

    public function calendrier($slug){




        $formation = Formations::where('slug', $slug)->first();
        if ($slug == 'tables-de-conversation') {
             return view('formations.tabledeconversation', compact('formation', 'slug'));
        } else {
            $calendars = Calendar::whereStatus(1)->where('formations_id', $formation->id)->get();
            return view('formations.calendrier', compact('calendars', 'slug', 'formation'));
        }


    }



    public function formation($slug, $formation, $id){


        $inscription = Calendar::where('slug', $formation)->where('id', $id)->whereStatus(1)->first();
        $formation = Formations::where('slug', $slug)->whereStatus(1)->first();


        return view('formations.inscription.formation', compact('slug', 'inscription', 'formation'));
    }

    public function courses($slug){


        return view('formations.courses', compact('slug'));
    }

    public function niveau($slug, $niveau){


        return view('formations.inscription.niveau', compact('slug', 'niveau'));
    }



}
