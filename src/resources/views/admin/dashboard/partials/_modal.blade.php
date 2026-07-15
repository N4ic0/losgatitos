<div x-show="modalOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4"
     x-cloak
     @keydown.escape.window="cerrarModal()">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="cerrarModal()"></div>
    <div class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-gradient-to-b from-[#1a1a2e] to-black rounded-3xl border border-white/10 shadow-2xl">
        {{-- Loading --}}
        <div x-show="loading" class="absolute inset-0 z-10 flex items-center justify-center bg-black/60 rounded-3xl">
            <svg class="animate-spin h-8 w-8 text-[#D4AF37]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>

        {{-- Header --}}
        <div class="p-6 border-b border-white/5 flex items-center justify-between" x-show="habitacion">
            <div class="flex items-center space-x-4">
                <span class="text-2xl font-bold text-white" x-text="'Hab. ' + habitacion.numero"></span>
                <span class="text-gray-400 text-sm" x-text="habitacion.categoria"></span>
                <span class="text-xs px-3 py-1 rounded-full font-medium"
                      :class="'bg-' + estadoColor(habitacion.estado) + '-500/20 text-' + estadoColor(habitacion.estado) + '-400'"
                      x-text="habitacion.estado"></span>
                <template x-if="habitacion.ultimo_estado && habitacion.estado !== 'Disponible'">
                    <span class="text-[#D4AF37] text-xs font-mono timer-modal"
                          :data-inicio="new Date(habitacion.ultimo_estado.fecha_inicio).getTime() / 1000">
                        <span class="tiempo-valor">00:00:00</span>
                    </span>
                </template>
            </div>
            <button @click="cerrarModal()" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="px-6 pt-4 border-b border-white/5 flex space-x-1 overflow-x-auto" x-show="habitacion">
            <button @click="activeTab = 'estado'" :class="activeTab === 'estado' ? 'text-[#D4AF37] border-[#D4AF37]' : 'text-gray-400 border-transparent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Estado</button>
            <button @click="activeTab = 'ocupacion'" :class="activeTab === 'ocupacion' ? 'text-[#D4AF37] border-[#D4AF37]' : 'text-gray-400 border-transparent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Ocupación</button>
            <button @click="activeTab = 'clientes'" :class="activeTab === 'clientes' ? 'text-[#D4AF37] border-[#D4AF37]' : 'text-gray-400 border-transparent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Clientes</button>
            <button @click="activeTab = 'consumos'" :class="activeTab === 'consumos' ? 'text-[#D4AF37] border-[#D4AF37]' : 'text-gray-400 border-transparent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Consumos</button>
            <button @click="activeTab = 'cobro'" :class="activeTab === 'cobro' ? 'text-[#D4AF37] border-[#D4AF37]' : 'text-gray-400 border-transparent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Cobro</button>
            <button @click="activeTab = 'historial'" :class="activeTab === 'historial' ? 'text-[#D4AF37] border-[#D4AF37]' : 'text-gray-400 border-transparent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Historial</button>
            <button @click="activeTab = 'observaciones'" :class="activeTab === 'observaciones' ? 'text-[#D4AF37] border-[#D4AF37]' : 'text-gray-400 border-transparent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Observaciones</button>
        </div>

        {{-- Tab Content --}}
        <div class="p-6">
            {{-- TAB: Estado --}}
            <div x-show="activeTab === 'estado'">
                <h3 class="text-white font-semibold mb-4">Cambiar Estado</h3>
                <div class="grid grid-cols-4 gap-3">
                    <template x-for="estado in ['Disponible', 'Reservada', 'Ocupada', 'Limpieza']" :key="estado">
                        <button @click="cambiarEstado(estado)"
                                :disabled="habitacion.estado === estado"
                                class="py-3 px-4 rounded-xl font-medium text-sm transition-all border"
                                :class="habitacion.estado === estado
                                    ? 'bg-[#D4AF37]/20 border-[#D4AF37]/40 text-[#D4AF37] cursor-not-allowed'
                                    : 'bg-white/5 border-white/10 text-gray-300 hover:bg-white/10 hover:border-[#D4AF37]/30'"
                                x-text="estado">
                        </button>
                    </template>
                </div>
                

                <template x-if="habitacion.estado === 'Disponible' || habitacion.estado === 'Reservada'">
                    <div class="mt-6 p-4 bg-[#D4AF37]/5 rounded-xl border border-[#D4AF37]/10">
                        <h4 class="text-white font-medium mb-3">Iniciar Ocupación</h4>
                        <div class="flex space-x-2 mb-3">
                            <button @click="tipoTiempo = '3h'; calcularTarifaPreview()"
                                    class="flex-1 py-3 px-4 rounded-xl font-medium text-sm transition-all border"
                                    :class="tipoTiempo === '3h'
                                        ? 'bg-[#D4AF37]/20 border-[#D4AF37]/40 text-[#D4AF37]'
                                        : 'bg-white/5 border-white/10 text-gray-300 hover:bg-white/10'">
                                3 Horas
                            </button>
                            <button @click="tipoTiempo = '8h'; calcularTarifaPreview()"
                                    class="flex-1 py-3 px-4 rounded-xl font-medium text-sm transition-all border"
                                    :class="tipoTiempo === '8h'
                                        ? 'bg-[#D4AF37]/20 border-[#D4AF37]/40 text-[#D4AF37]'
                                        : 'bg-white/5 border-white/10 text-gray-300 hover:bg-white/10'">
                                8 Horas
                            </button>
                        </div>
                        <template x-if="tarifaInfo">
                            <div class="mb-3 p-3 bg-white/5 rounded-xl text-xs space-y-1">
                                <div class="flex justify-between text-gray-400"><span>Tarifa:</span><span class="text-white" x-text="tarifaInfo.categoria + ' - ' + tarifaInfo.tipo_tiempo"></span></div>
                                <div class="flex justify-between text-gray-400"><span>Regla:</span><span class="text-[#D4AF37]" x-text="tarifaInfo.regla"></span></div>
                                <div class="flex justify-between text-gray-400"><span>Valor:</span><span class="text-green-400 font-bold" x-text="formatCurrency(tarifaInfo.precio)"></span></div>
                                <div class="flex justify-between text-gray-400"><span>Horario:</span><span class="text-white" x-text="tarifaInfo.hora_inicio + ' - ' + tarifaInfo.hora_termino"></span></div>
                            </div>
                        </template>
                        <template x-if="tarifaInfo">
                        <div class="mb-3 p-3 bg-white/5 rounded-xl border border-white/5">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300 text-sm">Personas adicionales</span>
                                <div class="flex items-center space-x-3">
                                    <button @click="personasAdicionales = Math.max(0, personasAdicionales - 1)" class="w-8 h-8 rounded-lg bg-white/10 text-white hover:bg-white/20 transition-all flex items-center justify-center font-bold text-lg">−</button>
                                    <span class="text-[#D4AF37] font-bold text-lg w-8 text-center" x-text="personasAdicionales"></span>
                                    <button @click="personasAdicionales++" class="w-8 h-8 rounded-lg bg-white/10 text-white hover:bg-white/20 transition-all flex items-center justify-center font-bold text-lg">+</button>
                                </div>
                            </div>
                            <template x-if="personasAdicionales > 0">
                                <div class="mt-2 pt-2 border-t border-white/5 flex justify-between text-xs">
                                    <span class="text-gray-400">+ 50% c/u:</span>
                                    <span class="text-[#D4AF37] font-bold" x-text="formatCurrency(Math.round(tarifaInfo.precio * 0.5 * personasAdicionales))"></span>
                                </div>
                            </template>
                        </div>
                        </template>
                        <button @click="iniciarOcupacion()" class="bg-green-600 hover:bg-green-500 text-white font-semibold px-6 py-3 rounded-xl transition-all w-full">
                            Iniciar Ocupación
                        </button>
                    </div>
                </template>
            </div>

            {{-- TAB: Ocupación --}}
            <div x-show="activeTab === 'ocupacion'">
                <template x-if="!ocupacion">
                    <div class="text-center py-8">
                        <p class="text-gray-400">No hay ocupación activa.</p>
                        <p class="text-gray-500 text-xs mt-1">Cambie el estado a Ocupada o inicie una ocupación desde la pestaña Estado.</p>
                    </div>
                </template>
                <template x-if="ocupacion">
                    <div class="space-y-4">
                        <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div><span class="text-gray-400">Inicio:</span> <span class="text-white ml-1" x-text="new Date(ocupacion.fecha_inicio).toLocaleString('es-CL')"></span></div>
                                <div><span class="text-gray-400">Tarifa base:</span> <span class="text-[#D4AF37] font-bold ml-1" x-text="formatCurrency(ocupacion.precio_base)"></span></div>
                                <template x-if="ocupacion.tarifa">
                                    <div class="col-span-2 bg-white/5 rounded-lg p-3 space-y-1 text-xs">
                                        <div class="flex justify-between"><span class="text-gray-400">Categoría:</span><span class="text-white" x-text="ocupacion.tarifa.categoria"></span></div>
                                        <div class="flex justify-between"><span class="text-gray-400">Tipo:</span><span class="text-white" x-text="ocupacion.tarifa.tipo_tiempo"></span></div>
                                        <div class="flex justify-between"><span class="text-gray-400">Horario:</span><span class="text-white" x-text="(ocupacion.tarifa.hora_inicio || '08:00') + ' - ' + (ocupacion.tarifa.hora_termino || '08:00')"></span></div>
                                    </div>
                                </template>
                                <template x-if="ocupacion.horas_beneficio > 0">
                                    <div><span class="text-gray-400">Beneficio:</span> <span class="text-green-400 ml-1" x-text="ocupacion.horas_beneficio + ' horas'"></span></div>
                                </template>
                            </div>
                        </div>

                        {{-- Promociones Aplicables --}}
                        <template x-if="!ocupacion.promocion && promocionesAplicables && promocionesAplicables.length > 0">
                            <template x-for="p in promocionesAplicables" :key="p.id">
                                <div class="bg-gradient-to-r from-[#D4AF37]/10 to-transparent rounded-xl p-4 border border-[#D4AF37]/20">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-[#D4AF37] font-semibold text-sm" x-text="'🎉 ' + p.titulo"></p>
                                            <p class="text-green-400 text-xs mt-1" x-text="'Beneficio: ' + p.horas_beneficio + ' horas'"></p>
                                            <template x-if="p.productos && p.productos.length > 0">
                                                <p class="text-gray-400 text-[10px] mt-1" x-text="'Incluye ' + p.productos.length + ' producto(s)'"></p>
                                            </template>
                                        </div>
                                        <button @click="tomarPromocion(p)" class="bg-green-600 hover:bg-green-500 text-white font-bold px-5 py-2 rounded-xl transition-all text-xs">
                                            Tomar
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </template>

                        {{-- Promoción activa --}}
                        <template x-if="ocupacion.promocion">
                            <div class="bg-gradient-to-r from-green-500/10 to-transparent rounded-xl p-4 border border-green-500/20">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-green-400 font-semibold text-sm">Promoción activa</p>
                                        <p class="text-white text-xs mt-1" x-text="ocupacion.promocion.titulo"></p>
                                        <p class="text-green-400 text-xs mt-1" x-text="'Beneficio: ' + ocupacion.horas_beneficio + ' horas'"></p>
                                    </div>
                                    <span class="text-green-400 text-[10px] bg-green-500/20 px-2 py-1 rounded-full">Activa</span>
                                </div>
                            </div>
                        </template>

                        <div class="flex space-x-3">
                            <button @click="finalizarOcupacion()" class="bg-red-600 hover:bg-red-500 text-white font-semibold px-6 py-3 rounded-xl transition-all text-sm flex-1">
                                Finalizar Ocupación
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            {{-- TAB: Clientes --}}
            <div x-show="activeTab === 'clientes'">
                <template x-if="!ocupacion">
                    <div class="text-center py-8"><p class="text-gray-400">No hay ocupación activa.</p></div>
                </template>
                <template x-if="ocupacion">
                    <div class="space-y-6">
                        {{-- Vehículo / Patente --}}
                        <div class="bg-white/5 rounded-xl p-4 border border-white/5 space-y-4">
                            <h4 class="text-white font-medium">Vehículo</h4>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="vehiculo" value="1" x-model="ocupacionVehiculo" @change="actualizarVehiculo()" class="text-[#D4AF37] focus:ring-[#D4AF37] bg-white/5 border-white/10">
                                    <span class="text-gray-300 text-sm">Vehículo</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="vehiculo" value="0" x-model="ocupacionVehiculo" @change="actualizarVehiculo()" class="text-[#D4AF37] focus:ring-[#D4AF37] bg-white/5 border-white/10">
                                    <span class="text-gray-300 text-sm">Peatón</span>
                                </label>
                            </div>
                            <template x-if="ocupacionVehiculo == 1">
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Patente</label>
                                    <input type="text" x-model="ocupacionPatente" @input="ocupacionPatente = ocupacionPatente.toUpperCase(); actualizarVehiculo()" maxlength="10" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm uppercase" placeholder="AAAA00">
                                </div>
                            </template>
                        </div>

                        <form x-ref="clienteForm" @submit.prevent="registrarCliente()" class="bg-white/5 rounded-xl p-4 border border-white/5 space-y-4">
                            <h4 class="text-white font-medium">Registrar Cliente</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Tipo Documento</label>
                                    <select name="tipo_documento" @change="if($event.target.value !== 'RUT') { rutValido = null; }" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                                        <option value="RUT">RUT</option>
                                        <option value="Pasaporte">Pasaporte</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">N° Documento</label>
                                    <div class="relative">
                                        <input type="text" name="numero_documento" required
                                               x-model="clienteDocumento"
                                               @input="clienteDocumento = clienteDocumento.toUpperCase(); validarRutInput()"
                                               :class="{'border-green-500': rutValido === true, 'border-red-500': rutValido === false, 'border-white/10': rutValido === null}"
                                               class="w-full bg-white/5 border rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm uppercase pr-8">
                                        <template x-if="rutValido === true">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-green-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </span>
                                        </template>
                                        <template x-if="rutValido === false">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Nombres</label>
                                    <input type="text" name="nombres" required x-model="clienteNombres" @input="clienteNombres = clienteNombres.toUpperCase()" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm uppercase">
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Apellidos</label>
                                    <input type="text" name="apellidos" required x-model="clienteApellidos" @input="clienteApellidos = clienteApellidos.toUpperCase()" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm uppercase">
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Nacionalidad</label>
                                    <input type="text" name="nacionalidad" value="Chilena" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Fecha Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" x-model="fechaNacimiento" @change="validarEdad()" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                                </div>
                            </div>
                            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all text-sm">Registrar</button>
                        </form>

                        {{-- Registered clients --}}
                        <template x-if="ocupacion.clientes && ocupacion.clientes.length > 0">
                            <div>
                                <h4 class="text-white font-medium mb-3">Clientes Registrados</h4>
                                <div class="space-y-2">
                                    <template x-for="c in ocupacion.clientes" :key="c.id">
                                        <div class="bg-white/5 rounded-xl px-4 py-3 border border-white/5 flex items-center justify-between">
                                            <div>
                                                <p class="text-white text-sm font-medium" x-text="c.nombres + ' ' + c.apellidos"></p>
                                                <p class="text-gray-400 text-xs" x-text="c.tipo_documento + ': ' + c.numero_documento"></p>
                                            </div>
                                            <span class="text-gray-500 text-xs" x-text="c.nacionalidad"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- TAB: Consumos --}}
            <div x-show="activeTab === 'consumos'">
                <template x-if="!ocupacion">
                    <div class="text-center py-8"><p class="text-gray-400">No hay ocupación activa.</p></div>
                </template>
                <template x-if="ocupacion">
                    <div class="space-y-6">
                        <div class="flex space-x-2">
                            <button @click="abrirSelectorConsumos()" class="flex-1 bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-3 rounded-xl transition-all text-sm">
                                + Agregar Consumo
                            </button>
                            <button @click="abrirSelectorCortesias()" class="flex-1 bg-purple-600 hover:bg-purple-500 text-white font-semibold px-5 py-3 rounded-xl transition-all text-sm">
                                + Agregar Cortesía
                            </button>
                            <template x-if="ocupacion.promocion && ocupacion.promocion.productos && ocupacion.promocion.productos.length > 0">
                                <button @click="agregarPromoProductos(ocupacion.promocion)" class="bg-green-600 hover:bg-green-500 text-white font-semibold px-5 py-3 rounded-xl transition-all text-sm">
                                    + Agregar Promoción
                                </button>
                            </template>
                            <template x-if="!ocupacion.promocion">
                                <template x-for="p in promocionesAplicables" :key="'promo-'+p.id">
                                    <template x-if="p.productos && p.productos.length > 0">
                                        <button @click="agregarPromoProductos(p)"
                                                class="w-full bg-green-600 hover:bg-green-500 text-white font-semibold px-5 py-3 rounded-xl transition-all text-sm"
                                                x-text="'+ Agregar ' + p.titulo">
                                        </button>
                                    </template>
                                </template>
                            </template>
                        </div>

                        {{-- Consumos registrados --}}
                        <template x-if="ocupacion.consumos && ocupacion.consumos.length > 0">
                            <div>
                                <h4 class="text-white font-medium mb-3">Consumos Registrados</h4>
                                <div class="bg-white/5 rounded-xl border border-white/5 divide-y divide-white/5">
                                    <template x-for="c in ocupacion.consumos" :key="c.id">
                                        <div class="px-4 py-3 flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-xs" :class="c.origen === 'Promocion' ? 'text-green-400' : 'text-white'"
                                                      x-text="c.producto?.nombre || 'Producto'"></span>
                                                <template x-if="c.origen === 'Promocion'">
                                                    <span class="text-[10px] bg-green-500/20 text-green-400 px-2 py-0.5 rounded-full">Promo</span>
                                                </template>
                                                <template x-if="c.origen !== 'Promocion'">
                                                    <div class="flex items-center space-x-1">
                                                        <button @click="actualizarCantidadConsumo(c.id, c.cantidad - 1)" class="w-6 h-6 rounded-md bg-white/10 text-white hover:bg-white/20 transition-all flex items-center justify-center text-xs font-bold">−</button>
                                                        <span class="text-[#D4AF37] text-xs font-bold min-w-[16px] text-center" x-text="c.cantidad"></span>
                                                        <button @click="actualizarCantidadConsumo(c.id, c.cantidad + 1)" class="w-6 h-6 rounded-md bg-white/10 text-white hover:bg-white/20 transition-all flex items-center justify-center text-xs font-bold">+</button>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-[#D4AF37] text-sm font-mono" x-text="c.total === 0 ? 'Gratis' : formatCurrency(c.total)"></span>
                                                <template x-if="c.origen !== 'Promocion'">
                                                    <button @click="eliminarConsumoItem(c.id)" class="text-red-400 hover:text-red-300 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- TAB: Cobro --}}
            <div x-show="activeTab === 'cobro'">
                <template x-if="!ocupacion">
                    <div class="text-center py-8"><p class="text-gray-400">No hay ocupación activa.</p></div>
                </template>
                <template x-if="ocupacion">
                    <div class="space-y-6">
                        <div class="bg-white/5 rounded-xl p-4 border border-white/5 space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-white/5">
                                <span class="text-gray-300 text-sm">Valor Habitación</span>
                                <span class="text-white font-bold" x-text="formatCurrency(ocupacion.precio_base)"></span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-white/5">
                                <span class="text-gray-300 text-sm">Consumos</span>
                                <span class="text-white font-bold" x-text="formatCurrency((ocupacion.consumos || []).filter(c => c.origen === 'Consumo').reduce((s, c) => s + c.total, 0))"></span>
                            </div>
                            <template x-if="ocupacion.promocion">
                                <div class="flex justify-between items-center py-2 border-b border-white/5">
                                    <span class="text-gray-300 text-sm">Promoción</span>
                                    <span class="text-green-400 text-sm font-medium" x-text="ocupacion.promocion.titulo"></span>
                                </div>
                            </template>
                            <div class="flex justify-between items-center py-2 text-lg">
                                <span class="text-white font-bold">Total</span>
                                <span class="text-[#D4AF37] font-bold" x-text="formatCurrency(totalOcupacion())"></span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-green-400 text-sm">Pagado</span>
                                <span class="text-green-400 font-bold" x-text="formatCurrency(totalPagado())"></span>
                            </div>
                            <div class="flex justify-between items-center py-2" :class="saldoPendiente() > 0 ? 'text-red-400' : 'text-green-400'">
                                <span class="text-sm">Saldo Pendiente</span>
                                <span class="font-bold cursor-pointer hover:text-[#D4AF37] transition-colors"
                                      @click="$refs.pagoMonto.value = saldoPendiente()"
                                      x-text="formatCurrency(saldoPendiente())"></span>
                            </div>
                        </div>

                        <div class="bg-white/5 rounded-xl p-4 border border-white/5 space-y-4">
                            <h4 class="text-white font-medium">Registrar Pago</h4>
                            <div class="flex space-x-3">
                                <input type="number" x-ref="pagoMonto" placeholder="Monto" min="1" :max="saldoPendiente()" class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                                <select x-ref="pagoForma" class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="tarjeta">Tarjeta</option>
                                </select>
                            </div>
                            <button @click="registrarPago()" class="bg-green-600 hover:bg-green-500 text-white font-semibold px-6 py-3 rounded-xl transition-all text-sm w-full">
                                Registrar Pago
                            </button>
                        </div>

                        {{-- Pagos registrados --}}
                        <template x-if="ocupacion.pagos && ocupacion.pagos.length > 0">
                            <div>
                                <h4 class="text-white font-medium mb-3">Pagos Registrados</h4>
                                <div class="bg-white/5 rounded-xl border border-white/5 divide-y divide-white/5">
                                    <template x-for="p in ocupacion.pagos" :key="p.id">
                                        <div class="px-4 py-3 flex items-center justify-between">
                                            <span class="text-gray-300 text-sm" x-text="new Date(p.created_at).toLocaleString('es-CL')"></span>
                                            <div class="flex items-center space-x-3">
                                                <span class="text-xs text-gray-400 uppercase" x-text="p.forma_pago"></span>
                                                <span class="text-green-400 font-bold" x-text="formatCurrency(p.monto)"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- TAB: Historial --}}
            <div x-show="activeTab === 'historial'">
                <template x-if="!ocupacion">
                    <div class="text-center py-8"><p class="text-gray-400">No hay ocupación activa.</p></div>
                </template>
                <template x-if="ocupacion">
                    <div class="space-y-4">
                        <h4 class="text-white font-medium mb-3">Línea de Tiempo</h4>
                        <div class="space-y-3">
                            {{-- Estados --}}
                            <template x-if="ocupacion.historial_estados">
                                <template x-for="h in ocupacion.historial_estados" :key="h.id">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-2 h-2 mt-2 rounded-full flex-shrink-0"
                                             :class="'bg-' + estadoColor(h.estado) + '-400'"></div>
                                        <div>
                                            <p class="text-white text-sm" x-text="'Estado: ' + h.estado"></p>
                                            <p class="text-gray-500 text-xs" x-text="new Date(h.fecha_inicio).toLocaleString('es-CL') + (h.fecha_fin ? ' → ' + new Date(h.fecha_fin).toLocaleString('es-CL') : '')"></p>
                                        </div>
                                    </div>
                                </template>
                            </template>

                            {{-- Consumos --}}
                            <template x-for="c in (ocupacion.consumos || [])" :key="'c' + c.id">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 mt-2 rounded-full flex-shrink-0 bg-orange-400"></div>
                                    <div>
                                        <p class="text-white text-sm" x-text="'Consumo: ' + (c.producto?.nombre || '') + (c.cantidad > 1 ? ' x' + c.cantidad : '')"></p>
                                        <p class="text-gray-500 text-xs" x-text="new Date(c.created_at).toLocaleString('es-CL')"></p>
                                    </div>
                                </div>
                            </template>

                            {{-- Pagos --}}
                            <template x-for="p in (ocupacion.pagos || [])" :key="'p' + p.id">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 mt-2 rounded-full flex-shrink-0 bg-green-400"></div>
                                    <div>
                                        <p class="text-white text-sm" x-text="'Pago: ' + formatCurrency(p.monto) + ' (' + p.forma_pago + ')'"></p>
                                        <p class="text-gray-500 text-xs" x-text="new Date(p.created_at).toLocaleString('es-CL')"></p>
                                    </div>
                                </div>
                            </template>

                            {{-- Observaciones --}}
                            <template x-for="o in (ocupacion.observaciones || [])" :key="'o' + o.id">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 mt-2 rounded-full flex-shrink-0 bg-blue-400"></div>
                                    <div>
                                        <p class="text-white text-sm" x-text="o.contenido"></p>
                                        <p class="text-gray-500 text-xs" x-text="new Date(o.created_at).toLocaleString('es-CL') + (o.user ? ' por ' + o.user.name : '')"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            {{-- TAB: Observaciones --}}
            <div x-show="activeTab === 'observaciones'">
                <template x-if="!ocupacion">
                    <div class="text-center py-8"><p class="text-gray-400">No hay ocupación activa.</p></div>
                </template>
                <template x-if="ocupacion">
                    <div class="space-y-6">
                        <div class="flex space-x-3">
                            <input type="text" x-ref="obsInput" @keydown.enter="agregarObservacion()" placeholder="Escriba una observación..." class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                            <button @click="agregarObservacion()" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all text-sm">Agregar</button>
                        </div>
                        <template x-if="ocupacion.observaciones && ocupacion.observaciones.length > 0">
                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                <template x-for="o in ocupacion.observaciones" :key="o.id">
                                    <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                                        <p class="text-gray-300 text-sm" x-text="o.contenido"></p>
                                        <p class="text-gray-500 text-xs mt-1" x-text="new Date(o.created_at).toLocaleString('es-CL') + (o.user ? ' - ' + o.user.name : '')"></p>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>