@extends('template')

@section('page-title', 'Configuration')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Modifier le Taux</h2>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif
    
    <form action="{{ route('update-taux') }}" method="POST">
        @csrf 
        <div class="form-group mb-3">
            <label for="taux" class="form-label">Nouveau Taux</label>
            <input type="number" step="0.01" class="form-control" id="taux" name="taux" placeholder="Entrez le nouveau taux" required>
        </div>
        <div class="form-group mb-3">
            <label for="date_modification" class="form-label">Date de Modification</label>
            <input type="date" class="form-control" id="date_modification" name="date_modification" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
