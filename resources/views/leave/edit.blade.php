@extends('layouts.template')

@section('content')

<h1 class="app-page-title">Congés</h1>
<hr class="mb-4">
<div class="row g-4 settings-section">
    <div class="col-12 col-md-4">
        <h3 class="section-title">Ajout</h3>
        <div class="section-intro">Ajouter un congé</div>
    </div>
    <div class="col-12 col-md-8">
        <div class="app-card app-card-settings shadow-sm p-4">
            <div class="app-card-body">
                <form class="settings-form" method="POST" action="{{ route('leave.update', $leave->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" placeholder="Entrer le nom" name="nom" required value="{{ $leave->nom }}">
                        @error('nom')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="type_conge" class="form-label">Type de Congé</label>
                        <input type="text" class="form-control" id="type_conge" placeholder="Entrer le type de congé" name="type_conge" required value="{{ $leave->type_conge }}">
                        @error('type_conge')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="date_de_depart" class="form-label">Date de Départ</label>
                        <input type="date" class="form-control" id="date_de_depart" name="date_de_depart" required value="{{ $leave->date_de_depart }}">
                        @error('date_de_depart')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date_de_fin" class="form-label">Date de Fin</label>
                        <input type="date" class="form-control" id="date_de_fin" name="date_de_fin" required value="{{ $leave->date_de_fin }}">
                        @error('date_de_fin')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" placeholder="Entrer la description" name="description" required>{{ $leave->description }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn app-btn-primary" >Mettre à jour</button>
                </form>
            </div><!--//app-card-body-->
        </div><!--//app-card-->
    </div>
</div><!--//row-->

@endsection
