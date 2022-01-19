@extends('layouts.app')

@section('asset_init')
    
@endsection

@section('content')
    <div class="card radius-15">
        <div class="card-header">
            Configuración de residencia
        </div>

        <div class="card-body">
            <form action="{{ route('configuracion.save') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <h6>Valores para pagos</h6>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Valor administración</label>
                            <input type="number" class="form-control" name="valor_administracion" step=".01"
                            value="{{ $informacion ? $informacion->valor_administracion  : '' }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Día limite de pago</label>
                            <select class="form-control" name="limite_pago" id="limite_pago" required>
                                <option value="">Elige una opción</option>
                                <option>Primer dia del mes</option>
                                <option>Mediados del mes</option>
                                <option>Último dia del mes</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Porcentaje interés mora(%)</label>
                            <input type="number" class="form-control" name="interes_mora"
                            value="{{ $informacion ? $informacion->interes_mora  : '' }}" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <h6>Valores de parqueaderos</h6>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Número de parqueaderos</label>
                            <input type="number" class="form-control" name="numero_parqueaderos"
                            value="{{ $informacion ? $informacion->numero_parqueaderos  : '' }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Horas gratis</label>
                            <input type="number" class="form-control" name="horas_gratis"
                            value="{{ $informacion ? $informacion->horas_gratis  : '' }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Valor hora adicional</label>
                            <input type="number" class="form-control" name="valor_hora_adicional"
                            value="{{ $informacion ? $informacion->valor_hora_adicional  : '' }}" step=".01" required>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('asset_end')
    <script>
        $(document).ready(function(){
            $("#limite_pago").val("{{ $informacion ? $informacion->limite_pago : ''  }}");
        })
    </script>
@endsection