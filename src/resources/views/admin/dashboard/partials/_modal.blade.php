<div class="modal fade" id="roomModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 960px;">
        <div class="modal-content position-relative" style="background: linear-gradient(to bottom, #1a1a2e, #000); border: 1px solid rgba(255,255,255,0.1); border-radius: 1.5rem;">

            {{-- Loading --}}
            <div id="modal-loading" class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="z-index: 10; background: rgba(0,0,0,0.6); border-radius: 1.5rem;">
                <svg class="animate-spin" style="height: 2rem; width: 2rem; color: #D4AF37;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            {{-- Header --}}
            <div id="modal-header" class="d-none px-4 pt-4 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <div class="d-flex align-items-center gap-3">
                    <span class="text-2xl font-bold text-white" id="modal-hab-numero"></span>
                    <span class="text-gray-400 text-sm" id="modal-hab-categoria"></span>
                    <span id="modal-hab-estado-badge" class="text-xs px-3 py-1 rounded-full font-medium"></span>
                    <span id="modal-timer" class="text-[#D4AF37] text-xs font-mono" style="display:none;">
                        <span class="tiempo-valor">00:00:00</span>
                    </span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            {{-- Tabs --}}
            <ul id="modal-tabs" class="d-none nav nav-tabs px-4 pt-3 border-0" style="border-bottom: 1px solid rgba(255,255,255,0.05); gap: 0; overflow-x: auto; flex-wrap: nowrap;" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active custom-tab-link" id="tab-estado-btn" data-bs-toggle="tab" data-bs-target="#tab-estado" type="button" role="tab" aria-controls="tab-estado" aria-selected="true">Estado</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link custom-tab-link" id="tab-ocupacion-btn" data-bs-toggle="tab" data-bs-target="#tab-ocupacion" type="button" role="tab" aria-controls="tab-ocupacion" aria-selected="false">Ocupación</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link custom-tab-link" id="tab-clientes-btn" data-bs-toggle="tab" data-bs-target="#tab-clientes" type="button" role="tab" aria-controls="tab-clientes" aria-selected="false">Clientes</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link custom-tab-link" id="tab-consumos-btn" data-bs-toggle="tab" data-bs-target="#tab-consumos" type="button" role="tab" aria-controls="tab-consumos" aria-selected="false">Consumos</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link custom-tab-link" id="tab-cobro-btn" data-bs-toggle="tab" data-bs-target="#tab-cobro" type="button" role="tab" aria-controls="tab-cobro" aria-selected="false">Cobro</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link custom-tab-link" id="tab-historial-btn" data-bs-toggle="tab" data-bs-target="#tab-historial" type="button" role="tab" aria-controls="tab-historial" aria-selected="false">Historial</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link custom-tab-link" id="tab-observaciones-btn" data-bs-toggle="tab" data-bs-target="#tab-observaciones" type="button" role="tab" aria-controls="tab-observaciones" aria-selected="false">Observaciones</button>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content p-4" id="modal-body">

                {{-- TAB: Estado --}}
                <div class="tab-pane fade show active" id="tab-estado" role="tabpanel" aria-labelledby="tab-estado-btn">
                    <h3 class="text-white font-semibold mb-4">Cambiar Estado</h3>
                    <div id="estado-btns" class="d-grid gap-3" style="grid-template-columns: repeat(4, 1fr);"></div>

                    <div id="estado-iniciar-ocupacion" class="d-none mt-4 p-4" style="background: rgba(212,175,55,0.05); border-radius: 0.75rem; border: 1px solid rgba(212,175,55,0.1);">
                        <h4 class="text-white font-medium mb-3">Iniciar Ocupación</h4>
                        <div class="d-flex gap-2 mb-3">
                            <button onclick="dashboard.setTipoTiempo('3h')" class="flex-1 py-3 px-4 rounded-xl font-medium text-sm transition-all border tipo-tiempo-btn" data-tipo-tiempo="3h">3 Horas</button>
                            <button onclick="dashboard.setTipoTiempo('8h')" class="flex-1 py-3 px-4 rounded-xl font-medium text-sm transition-all border tipo-tiempo-btn" data-tipo-tiempo="8h">8 Horas</button>
                        </div>
                        <div id="tarifa-info" class="d-none mb-3 p-3" style="background: rgba(255,255,255,0.05); border-radius: 0.75rem;">
                            <div class="d-flex justify-content-between text-xs text-gray-400"><span>Tarifa:</span><span class="text-white" id="tarifa-categoria"></span></div>
                            <div class="d-flex justify-content-between text-xs text-gray-400 mt-1"><span>Regla:</span><span class="text-[#D4AF37]" id="tarifa-regla"></span></div>
                            <div class="d-flex justify-content-between text-xs text-gray-400 mt-1"><span>Valor:</span><span class="text-green-400 font-bold" id="tarifa-valor"></span></div>
                            <div class="d-flex justify-content-between text-xs text-gray-400 mt-1"><span>Horario:</span><span class="text-white" id="tarifa-horario"></span></div>
                        </div>
                        <div id="tarifa-personas-section" class="d-none mb-3 p-3" style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; border: 1px solid rgba(255,255,255,0.05);">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-gray-300 text-sm">Personas adicionales</span>
                                <div class="d-flex align-items-center gap-2">
                                    <button onclick="dashboard.cambiarPersonasAdicionales(-1)" class="btn-persona-btn" style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: rgba(255,255,255,0.1); color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.125rem;">−</button>
                                    <span id="personas-count" class="text-[#D4AF37] fw-bold fs-5" style="width: 2rem; text-align: center;">0</span>
                                    <button onclick="dashboard.cambiarPersonasAdicionales(1)" class="btn-persona-btn" style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: rgba(255,255,255,0.1); color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.125rem;">+</button>
                                </div>
                            </div>
                            <div id="personas-extra" class="d-none mt-2 pt-2" style="border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; font-size: 0.75rem;">
                                <span class="text-gray-400">+ 50% c/u:</span>
                                <span id="personas-extra-total" class="text-[#D4AF37] font-bold"></span>
                            </div>
                        </div>
                        <button onclick="dashboard.iniciarOcupacion()" class="w-100" style="background: #16a34a; color: #fff; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.75rem; border: none; cursor: pointer; transition: all 0.2s;">Iniciar Ocupación</button>
                    </div>
                </div>

                {{-- TAB: Ocupación --}}
                <div class="tab-pane fade" id="tab-ocupacion" role="tabpanel" aria-labelledby="tab-ocupacion-btn">
                    <div id="ocupacion-empty" class="text-center py-4">
                        <p class="text-gray-400">No hay ocupación activa.</p>
                        <p class="text-gray-500 text-xs mt-1">Cambie el estado a Ocupada o inicie una ocupación desde la pestaña Estado.</p>
                    </div>
                    <div id="ocupacion-content" class="d-none" style="max-width: 100%;">
                        <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                            <div class="d-grid gap-3" style="grid-template-columns: 1fr 1fr;">
                                <div><span class="text-gray-400">Inicio:</span> <span class="text-white ms-1" id="ocupacion-inicio"></span></div>
                                <div><span class="text-gray-400">Tarifa base:</span> <span class="text-[#D4AF37] font-bold ms-1" id="ocupacion-precio-base"></span></div>
                                <div id="ocupacion-tarifa-info" class="d-none d-grid gap-1" style="grid-column: span 2; background: rgba(255,255,255,0.05); border-radius: 0.5rem; padding: 0.75rem; font-size: 0.75rem;">
                                    <div class="d-flex justify-content-between"><span class="text-gray-400">Categoría:</span><span class="text-white" id="ocupacion-tarifa-categoria"></span></div>
                                    <div class="d-flex justify-content-between"><span class="text-gray-400">Tipo:</span><span class="text-white" id="ocupacion-tarifa-tipo"></span></div>
                                    <div class="d-flex justify-content-between"><span class="text-gray-400">Horario:</span><span class="text-white" id="ocupacion-tarifa-horario"></span></div>
                                </div>
                                <div id="ocupacion-beneficio" class="d-none"><span class="text-gray-400">Beneficio:</span> <span class="text-green-400 ms-1" id="ocupacion-beneficio-valor"></span></div>
                            </div>
                        </div>

                        <div id="ocupacion-promos-section" class="d-none mt-3">
                            <div id="ocupacion-promos-container" class="d-grid gap-2"></div>
                        </div>

                        <div id="ocupacion-promo-activa" class="d-none mt-3" style="background: linear-gradient(to right, rgba(22,163,74,0.1), transparent); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(22,163,74,0.2);">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="text-green-400 font-semibold text-sm">Promoción activa</p>
                                    <p class="text-white text-xs mt-1" id="ocupacion-promo-activa-titulo"></p>
                                    <p class="text-green-400 text-xs mt-1" id="ocupacion-promo-activa-beneficio"></p>
                                </div>
                                <span class="text-green-400" style="font-size: 0.625rem; background: rgba(22,163,74,0.2); padding: 0.25rem 0.5rem; border-radius: 999px; white-space: nowrap;">Activa</span>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-3">
                            <button onclick="dashboard.finalizarOcupacion()" class="flex-1" style="background: #dc2626; color: #fff; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.75rem; border: none; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;">
                                Finalizar Ocupación
                            </button>
                        </div>
                    </div>
                </div>

                {{-- TAB: Clientes --}}
                <div class="tab-pane fade" id="tab-clientes" role="tabpanel" aria-labelledby="tab-clientes-btn">
                    <div id="clientes-empty" class="text-center py-4"><p class="text-gray-400">No hay ocupación activa.</p></div>
                    <div id="clientes-content" class="d-none">
                        <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                            <h4 class="text-white font-medium mb-3">Vehículo</h4>
                            <div class="d-flex align-items-center gap-4 mb-3">
                                <label class="d-flex align-items-center gap-2" style="cursor: pointer;">
                                    <input type="radio" name="vehiculo" value="1" id="vehiculo-si" onchange="dashboard.onVehiculoChange(1)" class="form-check-input" style="accent-color: #D4AF37;">
                                    <span class="text-gray-300 text-sm">Vehículo</span>
                                </label>
                                <label class="d-flex align-items-center gap-2" style="cursor: pointer;">
                                    <input type="radio" name="vehiculo" value="0" id="vehiculo-no" onchange="dashboard.onVehiculoChange(0)" class="form-check-input" style="accent-color: #D4AF37;">
                                    <span class="text-gray-300 text-sm">Peatón</span>
                                </label>
                            </div>
                            <div id="patente-section" class="d-none">
                                <label class="text-gray-400 text-xs d-block mb-1">Patente</label>
                                <input type="text" id="ocupacion-patente" oninput="dashboard.onPatenteChange(this.value)" maxlength="10" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.75rem 1rem; color: #fff; outline: none; font-size: 0.875rem; text-transform: uppercase;" placeholder="AAAA00">
                            </div>
                        </div>

                        <form id="cliente-form" onsubmit="dashboard.registrarCliente(); return false;" class="mt-4" style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                            <h4 class="text-white font-medium mb-3">Registrar Cliente</h4>
                            <div class="d-grid gap-3" style="grid-template-columns: 1fr 1fr;">
                                <div>
                                    <label class="text-gray-400 text-xs d-block mb-1">Tipo Documento</label>
                                    <select name="tipo_documento" onchange="dashboard.onTipoDocumentoChange(this.value)" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.75rem 1rem; color: #fff; outline: none; font-size: 0.875rem;">
                                        <option value="RUT">RUT</option>
                                        <option value="Pasaporte">Pasaporte</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-gray-400 text-xs d-block mb-1">N° Documento</label>
                                    <div style="position: relative;">
                                        <input type="text" name="numero_documento" required
                                               id="cliente-documento"
                                               oninput="dashboard.onClienteDocumentoChange(this.value)"
                                               style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.75rem 2.5rem 0.75rem 1rem; color: #fff; outline: none; font-size: 0.875rem; text-transform: uppercase;">
                                        <span id="rut-icon" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%);"></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-gray-400 text-xs d-block mb-1">Nombres</label>
                                    <input type="text" name="nombres" required id="cliente-nombres" oninput="dashboard.onClienteInput('cliente-nombres', this.value)" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.75rem 1rem; color: #fff; outline: none; font-size: 0.875rem; text-transform: uppercase;">
                                </div>
                                <div>
                                    <label class="text-gray-400 text-xs d-block mb-1">Apellidos</label>
                                    <input type="text" name="apellidos" required id="cliente-apellidos" oninput="dashboard.onClienteInput('cliente-apellidos', this.value)" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.75rem 1rem; color: #fff; outline: none; font-size: 0.875rem; text-transform: uppercase;">
                                </div>
                                <div>
                                    <label class="text-gray-400 text-xs d-block mb-1">Nacionalidad</label>
                                    <input type="text" name="nacionalidad" value="Chilena" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.75rem 1rem; color: #fff; outline: none; font-size: 0.875rem;">
                                </div>
                                <div>
                                    <label class="text-gray-400 text-xs d-block mb-1">Fecha Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" onchange="dashboard.onClienteNacimientoChange(this.value)" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.75rem 1rem; color: #fff; outline: none; font-size: 0.875rem;">
                                </div>
                            </div>
                            <button type="submit" class="mt-3" style="background: #D4AF37; color: #000; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.75rem; border: none; cursor: pointer; font-size: 0.875rem;">Registrar</button>
                        </form>

                        <div id="clientes-registered" class="d-none mt-4">
                            <h4 class="text-white font-medium mb-3">Clientes Registrados</h4>
                            <div id="clientes-list" class="d-grid gap-2"></div>
                        </div>
                    </div>
                </div>

                {{-- TAB: Consumos --}}
                <div class="tab-pane fade" id="tab-consumos" role="tabpanel" aria-labelledby="tab-consumos-btn">
                    <div id="consumos-empty" class="text-center py-4"><p class="text-gray-400">No hay ocupación activa.</p></div>
                    <div id="consumos-content" class="d-none">
                        <div class="d-flex gap-2 mb-3">
                            <button onclick="dashboard.abrirSelectorConsumos()" class="flex-1" style="background: #D4AF37; color: #000; font-weight: 600; padding: 0.75rem 1.25rem; border-radius: 0.75rem; border: none; cursor: pointer; font-size: 0.875rem;">+ Agregar Consumo</button>
                            <button onclick="dashboard.abrirSelectorCortesias()" class="flex-1" style="background: #9333ea; color: #fff; font-weight: 600; padding: 0.75rem 1.25rem; border-radius: 0.75rem; border: none; cursor: pointer; font-size: 0.875rem;">+ Agregar Cortesía</button>
                            <div id="consumos-promo-btns" class="d-flex gap-2"></div>
                        </div>

                        <div id="consumos-registered" class="d-none">
                            <h4 class="text-white font-medium mb-3">Consumos Registrados</h4>
                            <div id="consumos-list" style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; border: 1px solid rgba(255,255,255,0.05);"></div>
                        </div>
                    </div>
                </div>

                {{-- TAB: Cobro --}}
                <div class="tab-pane fade" id="tab-cobro" role="tabpanel" aria-labelledby="tab-cobro-btn">
                    <div id="cobro-empty" class="text-center py-4"><p class="text-gray-400">No hay ocupación activa.</p></div>
                    <div id="cobro-content" class="d-none">
                        <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <span class="text-gray-300 text-sm">Valor Habitación</span>
                                <span class="text-white font-bold" id="cobro-valor-base"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <span class="text-gray-300 text-sm">Consumos</span>
                                <span class="text-white font-bold" id="cobro-consumos"></span>
                            </div>
                            <div id="cobro-promo-row" class="d-none py-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <span class="text-gray-300 text-sm">Promoción</span>
                                <span class="text-green-400 text-sm fw-medium" id="cobro-promo-titulo"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2" style="font-size: 1.125rem;">
                                <span class="text-white font-bold">Total</span>
                                <span class="text-[#D4AF37] font-bold" id="cobro-total"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-green-400 text-sm">Pagado</span>
                                <span class="text-green-400 font-bold" id="cobro-pagado"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-sm">Saldo Pendiente</span>
                                <span id="cobro-saldo" class="font-bold" style="cursor: pointer;" onclick="dashboard.setSaldoPendiente()"></span>
                            </div>
                        </div>

                        <div class="mt-4" style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                            <h4 class="text-white font-medium mb-3">Registrar Pago</h4>
                            <div class="d-flex gap-3">
                                <input type="number" id="pago-monto" placeholder="Monto" min="1" style="flex: 1; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.75rem 1rem; color: #fff; outline: none; font-size: 0.875rem;">
                                <select id="pago-forma" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.75rem 1rem; color: #fff; outline: none; font-size: 0.875rem;">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="tarjeta">Tarjeta</option>
                                </select>
                            </div>
                            <button onclick="dashboard.registrarPago()" class="w-100 mt-3" style="background: #16a34a; color: #fff; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.75rem; border: none; cursor: pointer; font-size: 0.875rem;">
                                Registrar Pago
                            </button>
                        </div>

                        <div id="pagos-registered" class="d-none mt-4">
                            <h4 class="text-white font-medium mb-3">Pagos Registrados</h4>
                            <div id="pagos-list" style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; border: 1px solid rgba(255,255,255,0.05);"></div>
                        </div>
                    </div>
                </div>

                {{-- TAB: Historial --}}
                <div class="tab-pane fade" id="tab-historial" role="tabpanel" aria-labelledby="tab-historial-btn">
                    <div id="historial-empty" class="text-center py-4"><p class="text-gray-400">No hay ocupación activa.</p></div>
                    <div id="historial-content" class="d-none">
                        <h4 class="text-white font-medium mb-3">Línea de Tiempo</h4>
                        <div id="historial-timeline" class="d-grid gap-3"></div>
                    </div>
                </div>

                {{-- TAB: Observaciones --}}
                <div class="tab-pane fade" id="tab-observaciones" role="tabpanel" aria-labelledby="tab-observaciones-btn">
                    <div id="obs-empty" class="text-center py-4"><p class="text-gray-400">No hay ocupación activa.</p></div>
                    <div id="obs-content" class="d-none">
                        <div class="d-flex gap-3 mb-3">
                            <input type="text" id="obs-input" onkeydown="dashboard.onObsKeydown(event)" placeholder="Escriba una observación..." style="flex: 1; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.75rem 1rem; color: #fff; outline: none; font-size: 0.875rem;">
                            <button onclick="dashboard.agregarObservacion()" style="background: #D4AF37; color: #000; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.75rem; border: none; cursor: pointer; font-size: 0.875rem; white-space: nowrap;">Agregar</button>
                        </div>
                        <div id="observaciones-registered" class="d-none">
                            <div id="observaciones-list" class="d-grid gap-3" style="max-height: 15rem; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.nav-link.custom-tab-link {
    color: #9ca3af;
    border-bottom: 2px solid transparent;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    background: none;
    border-top: none;
    border-left: none;
    border-right: none;
    white-space: nowrap;
    transition: color 0.2s, border-color 0.2s;
    border-radius: 0;
    margin: 0;
}
.nav-link.custom-tab-link:hover {
    color: #e5e7eb;
    isolation: auto;
}
.nav-link.custom-tab-link.active {
    color: #D4AF37;
    border-bottom-color: #D4AF37;
    background: none;
}
.nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover {
    border-color: transparent;
    isolation: auto;
}
.btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.7;
}
.btn-close:hover {
    opacity: 1;
}
.form-check-input:checked {
    background-color: #D4AF37;
    border-color: #D4AF37;
}
</style>
