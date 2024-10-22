@extends('adminlte::page')

@section('title', '| Documentos')

@section('content_header')
<h1>Gestor de documentos</h1>
@stop

@section('content')
<p>En este modulo puedes hacer gestion de los archivos que son utilizados en las afiliaciones que realizas,
    ten en cuenta que los documentos pueden ser modificados siempre y cuando las posiciones de las variables se
    mantengan.</p>
<br>
<br><br>
<div class="row">
    <div class="col-md-12">
        <!-- Controller response -->
        @if (session()->get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Display validation errors -->
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-secondary">
                <h2 class="card-title">Plantilla EstaSSeguro.pdf</h2>
                <span class="float-right"><i class="fa fa-file" aria-hidden="true"></i></span>
            </div>
            <div class="card-body">
                <span>
                    Este documento es utilizado por la aseguradora para formalizar la afiliación del asegurado,
                    permitiendo acceder a los beneficios del seguro.
                </span>
                <br><br>
                <div class="row">
                    @foreach ($documents as $document)
                    @if ($document->estasseguro_document === null)
                    @if(Auth::user()->hasRole('Administrador'))
                    <div class="col-10 col-sm-10">
                        <span class="text-success">Carga tu plantilla aquí</span>
                    </div>
                    <div class="col-2 col-sm-2 text-end">
                        <button type="button" class="btn btn-success btn-sm" title="Cargar PDF"
                            data-bs-toggle="modal" data-bs-target="#uploadEstasseguro">
                            <i class="fas fa-upload"></i>
                        </button>
                    </div>
                    @else
                    <div class="col-10 col-sm-12">
                        <span class="text-success">Dile a tu Admin para cargar la plantilla</span>
                    </div>
                    @endif
                    @else
                    <div class="col-8 col-sm-8">
                        <span class="text-info">Estasseguro.pdf</span>
                    </div>
                    @if(Auth::user()->hasRole('Administrador'))
                    <div class="col-2 col-sm-2 text-end mb-2">
                        <button type="button" class="btn btn-info btn-sm" title="Actualizar archivo"
                            data-bs-toggle="modal" data-bs-target="#uploadEstasseguro">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                    @endif
                    <div class="col-2 col-sm-2 text-end mb-2">
                        <a href="{{ asset('storage/' . $document->estasseguro_document) }}" target="_blank"
                            class="btn btn-info btn-sm" title="Ver Documento">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-secondary">
                <h2 class="card-title">Plantilla Libranza.pdf</h2>
                <span class="float-right"><i class="fa fa-file" aria-hidden="true"></i></span>
            </div>
            <div class="card-body">
                <span>
                    Este documento es utilizado por la aseguradora para formalizar la afiliación del asegurado,
                    permitiendo acceder a los beneficios del seguro.
                </span>
                <br><br>
                <div class="row">
                    @foreach ($documents as $document)
                    @if ($document->libranza_document === null)
                    @if(Auth::user()->hasRole('Administrador'))
                    <div class="col-10">
                        <span class="text-success">Carga tu plantilla aqui</span>
                    </div>
                    <div class="col-2 mb-2">
                        <button type="button" class="btn btn-success btn-sm" title="Cargar PDF"
                            data-bs-toggle="modal" data-bs-target="#uploadLibranza">
                            <i class="fas fa-upload"></i>
                        </button>
                    </div>
                    @else
                    <div class="col-10 col-sm-12">
                        <span class="text-success">Dile a tu Admin para cargar la plantilla</span>
                    </div>
                    @endif
                    @else
                    <div class="col-8">
                        <span class="text-info">Libranza.pdf</span>
                    </div>
                    @if(Auth::user()->hasRole('Administrador'))
                    <div class="col-2 mb-2">
                        <button type="button" class="btn btn-info btn-sm" title="Actualizar archivo"
                            data-bs-toggle="modal" data-bs-target="#uploadLibranza">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                    @endif
                    <div class="col-2 mb-2">
                        <a href="{{ asset('storage/' . $document->libranza_document) }}" target="_blank"
                            class="btn btn-info btn-sm" title="Ver Documento">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-secondary">
                <h2 class="card-title">Plantilla Debito.pdf</h2>
                <span class="float-right"><i class="fa fa-file" aria-hidden="true"></i></span>
            </div>
            <div class="card-body">
                <span>
                    Este documento es utilizado por la aseguradora para formalizar la afiliación del asegurado,
                    permitiendo acceder a los beneficios del seguro.
                </span>
                <br><br>
                <div class="row">
                    @foreach ($documents as $document)
                    @if ($document->debito_document === null)
                    @if(Auth::user()->hasRole('Administrador'))
                    <div class="col-10">
                        <span class="text-success">Carga tu plantilla aqui</span>
                    </div>
                    <div class="col-2 mb-2">
                        <button type="button" class="btn btn-success btn-sm" title="Cargar PDF"
                            data-bs-toggle="modal" data-bs-target="#uploadDebito">
                            <i class="fas fa-upload"></i>
                        </button>
                    </div>
                    @else
                    <div class="col-10 col-sm-12">
                        <span class="text-success">Dile a tu Admin para cargar la plantilla</span>
                    </div>
                    @endif
                    @else
                    <div class="col-8">
                        <span class="text-info">Debito.pdf</span>
                    </div>
                    @if(Auth::user()->hasRole('Administrador'))
                    <div class="col-2 mb-2">
                        <button type="button" class="btn btn-info btn-sm" title="Actualizar archivo"
                            data-bs-toggle="modal" data-bs-target="#uploadDebito">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                    @endif
                    <div class="col-2 mb-2">
                        <a href="{{ asset('storage/' . $document->debito_document) }}" target="_blank"
                            class="btn btn-info btn-sm" title="Ver Documento">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal EstaSSeguro -->
<div class="modal fade" id="uploadEstasseguro" tabindex="-1" role="dialog"
    aria-labelledby="uploadEstasseguroLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadEstasseguroLabel">Archivo EstaSSeguro</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Recuerda que solo se pueden cargar documentos con extensión .PDF
                <form id="formEstaSSeguro" autocomplete="off" action="{{ route('documentos.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12 mt-3">
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="hidden" name="type_document" value="estasseguro">
                                    <input type="file" class="custom-file-input" name="document_path"
                                        id="document_path_estasseguro" accept=".pdf">
                                    <label class="custom-file-label" for="document_path_estasseguro">Seleccionar
                                        archivo</label>
                                </div>
                            </div>
                            <div id="document_path_estasseguro-error" class="error-message"
                                style="display:none; color: red;">El documento es obligatorio</div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="submitBtnEstaSSeguro">
                    <i class="fas fa-save mr-2"></i> Guardar PDF
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Libranza -->
<div class="modal fade" id="uploadLibranza" tabindex="-1" role="dialog" aria-labelledby="uploadLibranzaLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadLibranzaLabel">Archivo Libranza</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Recuerda que solo se pueden cargar documentos con extensión .PDF
                <form id="formLibranza" autocomplete="off" action="{{ route('documentos.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12 mt-3">
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="hidden" name="type_document" value="libranza">
                                    <input type="file" class="custom-file-input" name="document_path"
                                        id="document_path_libranza" accept=".pdf">
                                    <label class="custom-file-label" for="document_path_libranza">Seleccionar
                                        archivo</label>
                                </div>
                            </div>
                            <div id="document_path_libranza-error" class="error-message"
                                style="display:none; color: red;">El documento es obligatorio</div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="submitBtnLibranza">
                    <i class="fas fa-save mr-2"></i> Guardar PDF
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Debito -->
<div class="modal fade" id="uploadDebito" tabindex="-1" role="dialog" aria-labelledby="uploadDebitoLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDebitoLabel">Archivo Debito</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Recuerda que solo se pueden cargar documentos con extensión .PDF
                <form id="formDebito" autocomplete="off" action="{{ route('documentos.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12 mt-3">
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="hidden" name="type_document" value="debito">
                                    <input type="file" class="custom-file-input" name="document_path"
                                        id="document_path_debito" accept=".pdf">
                                    <label class="custom-file-label" for="document_path_debito">Seleccionar
                                        archivo</label>
                                </div>
                            </div>
                            <div id="document_path_debito-error" class="error-message"
                                style="display:none; color: red;">El documento es obligatorio</div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="submitBtnDebito">
                    <i class="fas fa-save mr-2"></i> Guardar PDF
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
    .is-invalid {
        border-color: red;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('js/documents.js') }}"></script>
@stop
