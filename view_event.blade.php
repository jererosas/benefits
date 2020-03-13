@extends('adminlte::page')

@section('title', 'Sistema de eventos | Panel | Evento |' . $event->name)

@section('content_header')
<h1 class="pl-2">Evento: <span style="text-transform: uppercase">{{ $event->name }}</span>, Fecha: {{ $event->date }}</h1>
@stop

@section('js')
<script type="text/javascript" defer>
    $(document).ready(function() {
        $(".select2").select2();
        $('#table').DataTable({
            language: {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });

        $('.sent').click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '/home/invitation/sent',
                data: {
                    'invitation': $(this).data("invitation")
                },
                beforeSend: function() {},
                success: function(result) {
                    if (result.status == "success") {
                        location.reload();
                    }
                },
            });
        });
    });
</script>
@stop

@section('content')
@if($user->role == "admin" || $user->role == "creator")
<div class="modal fade" id="generate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear invitación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('invitation_create') }}" method="POST" accept-charset="UTF-8">
                @csrf
                <div class="modal-body">
                    <input type="text" value="{{$event->id}}" name="event" id="event" hidden required>
                    <div class="form-group">
                        <label for="name" style="font-weight: 500;">Nombre</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                        @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="surname" style="font-weight: 500;">Apellido</label>
                        <input type="text" class="form-control" name="surname" id="surname" required>
                        @if ($errors->has('surname'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('surname') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group mt-2">
                        <label for="gender" style="font-weight: 500;">Género</label>
                        <select class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true" name="gender" id="gender" lang="es" required>
                            <option value="MASCULINO" @if(old('gender')=='MASCULINO' ) selected @endif>MASCULINO</option>
                            <option value="FEMENINO" @if(old('gender')=='FEMENINO' ) selected @endif>FEMENINO</option>
                            <option value="OTRO" @if(old('gender')=='OTRO' ) selected @endif>OTRO</option>
                        </select>
                        @if ($errors->has('gender'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('gender') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="whatsapp" style="font-weight: 500;">WhatsApp <small>(incluir código de país)</small></label>
                        <input type="text" class="form-control" name="whatsapp" id="whatsapp" value="+54" required>
                        @if ($errors->has('whatsapp'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('whatsapp') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group mt-2">
                        <label for="type" style="font-weight: 500;">Tipo</label>
                        <select class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true" name="type" id="type" lang="es" required>
                            <option value="RRPP" @if(old('type')=='RRPP' ) selected @endif>RRPP</option>
                            <option value="ANTICIPADA" @if(old('type')=='ANTICIPADA' ) selected @endif>ANTICIPADA</option>
                            <option value="VIP" @if(old('type')=='VIP' ) selected @endif>VIP</option>
                            <option value="INVITADO" @if(old('type')=='INVITADO' ) selected @endif>INVITADO</option>
                            <option value="OTRO" @if(old('type')=='OTRO' ) selected @endif>OTRO</option>
                        </select>
                        @if ($errors->has('type'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('type') }}</strong>
                        </span>
                        @endif
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="main_guest" style="font-weight: 500;">Invitado principal</label>
                        <input type="text" class="form-control" name="main_guest" id="main_guest" required>
                        @if ($errors->has('main_guest'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('main_guest') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="amount_of_people" style="font-weight: 500;">Cantidad de personas</small></label>
                        <input type="number" class="form-control" value="1" min="1" name="amount_of_people" id="amount_of_people" required>
                        @if ($errors->has('amount_of_people'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('amount_of_people') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="guest_names" style="font-weight: 500;">Nombre de los invitados 
                            <small>(separados por ,)</small> <br>
                            <small>Ej: Christian Espindola,Juan Perez,Jazmin Rodriguez</small> <br>
                            <small>La cantidad de nombres tiene que coincidir con el número de "Cantidad de personas"</small>
                        </label>
                        <input type="text" class="form-control" name="guest_names" id="guest_names" required>
                        @if ($errors->has('guest_names'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('guest_names') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
                    <button type="submit" class="btn btn-primary">CREAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if ($user->role == "admin" || $user->role == "creator")
<hr>

<div class="col-12 p-2 d-flex justify-content-center align-items-center">
    <button type="button" class="col-12 col-md-2 btn btn-primary" data-toggle="modal" data-target="#generate">CREAR INVITACIÓN</button>
</div>
@endif

<hr>

<div class="col-12 p-2 mt-3">
    <table id="table" class="table table-striped table-bordered table-hover dt-responsive nowrap display">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Género</th>
                <th>Invitado principal</th>
                <th>WhatsApp</th>
                <th>Cantidad de personas</th>
                <th>Enviado</th>
                <th>Confirmado</th>
                <th>Estado</th>
                <th>Tipo</th>
                @if ($user->role != "scanner")
                <th>Opciones</th>
                @endif
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Género</th>
                <th>Invitado principal</th>
                <th>WhatsApp</th>
                <th>Cantidad de personas</th>
                <th>Enviado</th>
                <th>Confirmado</th>
                <th>Estado</th>
                <th>Tipo</th>
                @if ($user->role != "scanner")
                <th>Opciones</th>
                @endif
            </tr>
        </tfoot>
        <tbody>
            @foreach ($invitations as $invitation)
            @php($c = true)
            @if ($invitation->child != null)
            @php($c = false)
            @endif
            @if ($user->role == "creator" && $user->id != $invitation->creator)
            @php($c = false)
            @endif
            @if($c == true)
            <tr>
                <td>{{ $invitation->name }}</td>
                <td>{{ $invitation->surname }}</td>
                <td>{{ $invitation->gender }}</td>
                <td>{{ $invitation->main_guest }}</td>
                <td>{{ $invitation->whatsapp }}</td>
                <td>{{ $invitation->amount_of_people }}</td>
                <td>{{ $invitation->sent }}</td>
                <td>{{ $invitation->confirmation }}</td>
                <td>{{ $invitation->status }}</td>
                <td>{{ $invitation->type }}</td>
                <td>
                    <ul class="list-inline">
                        @if ($user->role != "scanner")
                        <li class="list-inline-item">
                            <a href="https://api.whatsapp.com/send?phone={{$invitation->whatsapp}}&text={{route('qr', ['event' => $invitation->event, 'invitation' => $invitation->id])}}@foreach($invitations->where('child', $invitation->id) as $child)%0A{{route('qr', ['event' => $invitation->event, 'invitation' => $child->id])}}@endforeach" target="_blank" class="btn btn-out btn-square sent" data-invitation="{{$invitation->id}}" style="color: #128c7e; font-size: 1.2rem;" title="Compartir por whatsapp"><i class="fab fa-fw fa-whatsapp-square"></i></a>
                        </li>
                        @endif
                        @if ($user->role != "scanner")
                        @php ($v = false)
                        @if ($user->role == "creator" && $user->id == $invitation->creator)
                        @php($v = true)
                        @endif
                        @if ($v == true)
                        @if ($invitation->status != "CONFIRMADA" && $invitation->status != "INGRESADA")
                        <li class="list-inline-item">
                            <form action="{{ route('invitation_forcedelete') }}" method="POST">
                                @csrf
                                <input type="number" name="invitation" value="{{ $invitation->id }}" hidden>
                                <button class="btn btn-out btn-square" style="color: red; font-size: 1.2rem;" title="Forzar eliminado"><i class="fas fa-fw fa-trash"></i></button>
                            </form>
                        </li>   
                        @endif
                        @endif
                        @endif
                        @if ($user->role == "admin")
                        @if ($invitation->status == "ACTIVA")
                        <li class="list-inline-item">
                            <form action="{{ route('invitation_desactivate') }}" method="POST">
                                @csrf
                                <input type="number" name="invitation" value="{{ $invitation->id }}" hidden>
                                <button class="btn btn-out btn-square" style="color: orange; font-size: 1.2rem;" title="Desactivar"><i class="fas fa-fw fa-times"></i></button>
                            </form>
                        </li>
                        @elseif ($invitation->status == "CANCELADA")
                        <li class="list-inline-item">
                            <form action="{{ route('invitation_activate') }}" method="POST">
                                @csrf
                                <input type="number" name="invitation" value="{{ $invitation->id }}" hidden>
                                <button class="btn btn-out btn-square" style="color: green; font-size: 1.2rem;" title="Activar"><i class="fas fa-fw fa-plus"></i></button>
                            </form>
                        </li>
                        @endif
                        @endif
                    </ul>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@stop
