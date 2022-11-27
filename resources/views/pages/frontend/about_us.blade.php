@extends('layouts.web')

@section('content')
    @php
    $profesional = \App\Models\WebContent::where('name', 'Profesional')->first();
    $excellent = \App\Models\WebContent::where('name', 'Excellent')->first();
    $respect = \App\Models\WebContent::where('name', 'Respect')->first();
    $intelektualitas = \App\Models\WebContent::where('name', 'Intelektualitas')->first();
    $servis = \App\Models\WebContent::where('name', 'Servis')->first();
    $atraktif = \App\Models\WebContent::where('name', 'Atraktif')->first();
    $integritas = \App\Models\WebContent::where('name', 'Integritas')->first();
    $visi = \App\Models\WebContent::where('name', 'visi')->first();
    $misi = \App\Models\WebContent::where('name', 'misi')->first();
    @endphp
    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-header">
                <h3>Prinsip PAAI - <span class="text-warning">PERISAI</span></h3>
                <hr class="border-warning w-25 d-inline-block mt-0 mb-4" />
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-8 col-lg-9 col-md-10 col-12 offset-md-1 py-3">


                        <h5><span class="text-warning">P</span>rofesional</h5>
                        <p class="mb-4">
                            {!! $profesional->desc !!}
                        </p>

                        <h5><span class="text-warning">E</span>xcellent</h5>
                        <p class="mb-4">
                            {!! $excellent->desc !!}
                        </p>

                        <h5><span class="text-warning">R</span>espect</h5>
                        <p class="mb-4">
                            {!! $respect->desc !!}
                        </p>

                        <h5><span class="text-warning">I</span>ntelektualitas</h5>
                        <p class="mb-4">
                            {!! $intelektualitas->desc !!}
                        </p>

                        <h5><span class="text-warning">S</span>ervis</h5>
                        <p class="mb-4">
                            {!! $servis->desc !!}
                        </p>

                        <h5><span class="text-warning">A</span>traktif</h5>
                        <p class="mb-4">
                            {!! $atraktif->desc !!}
                        </p>

                        <h5><span class="text-warning">I</span>ntegritas</h5>
                        <p class="mb-5">
                            {!! $integritas->desc !!}
                        </p>

                        <h3>Visi & Misi</span></h3>
                        <hr class="border-warning w-25 d-inline-block mt-0 mb-4" />

                        <h5>Visi</h5>
                        <p class="mb-4">
                            {!! $visi->desc !!}
                        </p>

                        <h5>Misi</h5>
                        <p>
                            {!! $misi->desc !!}
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
