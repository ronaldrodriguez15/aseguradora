@extends('adminlte::page')

@section('title', 'Aseguradoras')

@section('content_header')
    <h1>Aseguradoras</h1>
@stop

@section('content')
    <p>En este modulo puedes ver todo el registro de las Aseguradoras, asimismo, puedes crear una nueva o editar las ya
        creadas.</p>
    <br><br><br>

    <div class="row justify-content-center">
        <div class="col-md-12 text-right mb-5">
            <a class="btn btn-success" id="btnNuevaCiudad">
                <i class="fas fa-plus-circle mr-2"></i>Nueva Aseguradora
            </a>
        </div>
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

            <!-- Mensajes de error -->
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
        <div class="col-md-12">
            <div class="card mb-5">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="text-center">
                                <tr class="table-dark text-white">
                                    <th>No Poliza</th>
                                    <th>Nombres</th>
                                    <th>Documentos</th>
                                    <th>Val Incapacidad</th>
                                    <th>Val Vida</th>
                                    <th>Val Previexequial</th>
                                    <th>Val Bancos</th>
                                    <th>Fecha creación</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($insurers as $insurer)
                                    <tr>
                                        <td>{{ $insurer['no_poliza'] }}</td>
                                        <td>{{ $insurer['name'] }}</td>
                                        <td>
                                            @if ($insurer['document_path'])
                                                <!-- Enlace para descargar o ver el documento -->
                                                <a href="{{ asset('storage/' . $insurer['document_path']) }}"
                                                    target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-file-pdf mr-2"></i> Ver documento
                                                </a>
                                            @else
                                                <span class="text-muted">No disponible</span>
                                            @endif
                                        </td>
                                        <td>{{ $insurer['val_incapacidad'] }}</td>
                                        <td>{{ $insurer['val_vida'] }}</td>
                                        <td>{{ $insurer['val_previexequial'] }}</td>
                                        <td>{{ $insurer['val_banco'] }}</td>
                                        <td>{{ $insurer['created_at']->format('Y-m-d - H:m') }}</td>
                                        <td>
                                            @if ($insurer['status'] === 1)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($insurer['status'] === 1)
                                                <div class="button-container">

                                                    <button class="btn btn-success btn-sm edit-btn"
                                                        data-id="{{ $insurer['id'] }}" data-toggle="modal"
                                                        data-target="#modalEditAseguradora">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <form action="{{ route('aseguradoras.destroy', $insurer['id']) }}"
                                                        method="POST" id="formDelete-{{ $insurer['id'] }}">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                            data-id="{{ $insurer['id'] }}" onclick="confirmDelete(this)"
                                                            title="Eliminar">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalNuevaCiudad" tabindex="-1" role="dialog" aria-labelledby="modalNuevaSedeLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevaCiudadLabel">Aseguradoras</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formNuevaAseguradora" autocomplete="off" action="{{ route('aseguradoras.store') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name">Nombre <span class="required">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Introduce el nombre de la aseguradora">
                                <div id="name-error" class="error-message">El nombre es obligatorio</div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="no_poliza">No Poliza <span class="required">*</span></label>
                                <input type="number" class="form-control" id="no_poliza" name="no_poliza"
                                    placeholder="Introduce el número de la poliza">
                                <div id="poliza-error" class="error-message">La poliza es obligatorio</div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="val_incapacidad">Valor Incapacidad <span class="required">*</span></label>
                                <input type="text" class="form-control" id="val_incapacidad" name="val_incapacidad"
                                    placeholder="Introduce el valor de la incapacidad" step="any" required>
                                <div id="poliza-error" class="error-message">El valor incapacidad es obligatorio</div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="val_vida">Valor vida <span class="required">*</span></label>
                                <input type="number" class="form-control" id="val_vida" name="val_vida"
                                    placeholder="Introduce el valor de la vida">
                                <div id="poliza-error" class="error-message">El valor vida es obligatorio</div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="val_previexequial">Valor previexequial <span class="required">*</span></label>
                                <input type="number" class="form-control" id="val_previexequial"
                                    name="val_previexequial" placeholder="Introduce el valor previexequial">
                                <div id="poliza-error" class="error-message">El valor previexequial es obligatorio</div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="val_banco">Valor banco <span class="required">*</span></label>
                                <input type="number" class="form-control" id="val_banco" name="val_banco"
                                    placeholder="Introduce el valor del banco">
                                <div id="poliza-error" class="error-message">El valor banco es obligatorio</div>
                            </div>
                            <div class="form-group col-md-12 mt-3">
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="document_path"
                                            id="document_path" accept=".pdf">
                                        <label class="custom-file-label" for="document_path"
                                            data-browse="Cargar* (PDF)">Seleccionar archivo</label>
                                    </div>
                                </div>
                                <div id="document_path-error" class="error-message">El documento es obligatorio</div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-2"></i> Guardar Aseguradora
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar aseguradora -->
    <div class="modal fade" id="modalEditAseguradora" tabindex="-1" role="dialog"
        aria-labelledby="modalEditAseguradoraLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditAseguradoraLabel">Editar Aseguradora</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditAseguradora" action="{{ route('aseguradoras.update', 'id') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="editId">
                        <div class="form-group">
                            <label for="editName">Nombre <span class="required">*</span></label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editNoPoliza">No Poliza <span class="required">*</span></label>
                            <input type="number" class="form-control" id="editNoPoliza" name="no_poliza" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="val_incapacidad">Valor Incapacidad <span class="required">*</span></label>
                            <input type="text" class="form-control" id="editValIncapacidad" name="val_incapacidad"
                                placeholder="Introduce el valor de la incapacidad">
                            <div id="poliza-error" class="error-message">El valor incapacidad es obligatorio</div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="val_vida">Valor vida <span class="required">*</span></label>
                            <input type="number" class="form-control" id="editValVida" name="val_vida"
                                placeholder="Introduce el valor de la vida">
                            <div id="poliza-error" class="error-message">El valor vida es obligatorio</div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="val_previexequial">Valor previexequial <span class="required">*</span></label>
                            <input type="number" class="form-control" id="editValPreviexequial" name="val_previexequial"
                                placeholder="Introduce el valor previexequial">
                            <div id="poliza-error" class="error-message">El valor previexequial es obligatorio</div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="val_banco">Valor banco <span class="required">*</span></label>
                            <input type="number" class="form-control" id="editValBanco" name="val_banco"
                                placeholder="Introduce el valor del banco">
                            <div id="poliza-error" class="error-message">El valor banco es obligatorio</div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="document_path"
                                        id="editDocumentPath" accept=".pdf">
                                    <label class="custom-file-label" for="editDocumentPath"
                                        data-browse="Cargar* (PDF)">Seleccionar archivo</label>
                                </div>
                            </div>
                            <div id="document_path-error" class="error-message">El documento es obligatorio</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="formEditAseguradora" class="btn btn-success">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tu archivo de script personalizado -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        const valIncapacidadInput = document.getElementById("val_incapacidad");

        // Reemplazar comas por puntos y permitir solo números y puntos
        valIncapacidadInput.addEventListener("input", function() {
            this.value = this.value.replace(/,/g, '.'); // Reemplaza comas por puntos
        });

        // Validación del formulario
        document.querySelector("form").addEventListener("submit", function(e) {
            const valor = parseFloat(valIncapacidadInput.value);
            if (isNaN(valor) || valor <= 0) {
                e.preventDefault(); // Evita el envío del formulario
                alert("Por favor, introduce un valor válido para la incapacidad.");
                valIncapacidadInput.focus(); // Lleva el foco al campo
            }
        });

        $(document).ready(function() {
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '/aseguradoras/' + id + '/edit',
                    type: 'GET',
                    success: function(response) {
                        // Pasa los datos al modal
                        $('#editId').val(response.id);
                        $('#editName').val(response.name);
                        $('#editNoPoliza').val(response.no_poliza);
                        $('#editValIncapacidad').val(response.val_incapacidad);
                        $('#editValVida').val(response.val_vida);
                        $('#editValPreviexequial').val(response.val_previexequial);
                        $('#editValBanco').val(response.val_banco);
                        $('#formEditAseguradora').attr('action', '/aseguradoras/' + response
                            .id);
                    }
                });
            });

            $('#formEditAseguradora').on('submit', function(e) {
                var isValid = true;

                // Validar el campo 'name'
                if ($('#editName').val().trim() === '') {
                    isValid = false;
                    alert('El campo Nombre es obligatorio.');
                }

                // Validar el campo 'no_poliza'
                if ($('#editNoPoliza').val().trim() === '') {
                    isValid = false;
                    alert('El campo No Poliza es obligatorio.');
                }

                if ($('#editDocumentPath').val() === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'El documento PDF es obligatorio.',
                        confirmButtonText: 'Aceptar'
                    });
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault(); // Detener el envío si la validación falla
                }
            });

            $('#editDocumentPath').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(
                    fileName);
            });
        });
    </script>
@stop
