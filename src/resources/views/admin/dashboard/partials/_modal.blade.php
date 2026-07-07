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
                <template x-if="habitacion.ultimo_estado">
                    <span class="text-[#D4AF37] text-xs font-mono timer-modal"
                          :data-inicio="habitacion.ultimo_estado.fecha_inicio">
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
                <div class="grid grid-cols-2 gap-3">
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
                <p class="text-gray-500 text-xs mt-4">Estado actual: <span class="text-white font-medium" x-text="habitacion.estado"></span></p>

                <template x-if="habitacion.estado === 'Disponible' || habitacion.estado === 'Reservada'">
                    <div class="mt-6 p-4 bg-[#D4AF37]/5 rounded-xl border border-[#D4AF37]/10">
                        <h4 class="text-white font-medium mb-3">Iniciar Ocupación</h4>
                        <select x-ref="promocionSelect" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white mb-3 outline-none focus:border-[#D4AF37]">
                            <option value="">Sin promoción</option>
                            <template x-for="p in promociones" :key="p.id">
                                <option :value="p.id" x-text="p.titulo"></option>
                            </template>
                        </select>
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
                                <div><span class="text-gray-400">Tarifa base:</span> <span class="text-white ml-1" x-text="formatCurrency(ocupacion.precio_base)"></span></div>
                                <template x-if="ocupacion.promocion">
                                    <div class="col-span-2"><span class="text-gray-400">Promoción:</span> <span class="text-[#D4AF37] ml-1" x-text="ocupacion.promocion.titulo"></span></div>
                                </template>
                                <template x-if="ocupacion.horas_beneficio > 0">
                                    <div><span class="text-gray-400">Beneficio:</span> <span class="text-green-400 ml-1" x-text="ocupacion.horas_beneficio + ' horas'"></span></div>
                                </template>
                            </div>
                        </div>
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
                        <form x-ref="clienteForm" @submit.prevent="registrarCliente()" class="bg-white/5 rounded-xl p-4 border border-white/5 space-y-4">
                            <h4 class="text-white font-medium">Registrar Cliente</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Tipo Documento</label>
                                    <select name="tipo_documento" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                                        <option value="RUT">RUT</option>
                                        <option value="Pasaporte">Pasaporte</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">N° Documento</label>
                                    <input type="text" name="numero_documento" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Nombres</label>
                                    <input type="text" name="nombres" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Apellidos</label>
                                    <input type="text" name="apellidos" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Nacionalidad</label>
                                    <input type="text" name="nacionalidad" value="Chilena" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Fecha Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-[#D4AF37] text-sm">
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
                        <div>
                            <h4 class="text-white font-medium mb-3">Agregar Consumo</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" x-data="{ cargando: false }" x-init="if (productos.length === 0) { fetch('/admin/dashboard/productos').then(r => r.json()).then(d => productos = d); }">
                                <template x-for="p in productos.filter(pr => pr.categoria === 'Producto')" :key="p.id">
                                    <div class="bg-white/5 rounded-xl p-3 border border-white/5 hover:border-[#D4AF37]/30 transition-all text-center cursor-pointer" @click="agregarConsumo(p.id)">
                                        <div class="w-full h-20 bg-white/10 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                                            <template x-if="p.imagen">
                                                <img :src="'/storage/' + p.imagen" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!p.imagen">
                                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                            </template>
                                        </div>
                                        <p class="text-white text-xs font-medium" x-text="p.nombre"></p>
                                        <p class="text-[#D4AF37] text-xs font-bold" x-text="formatCurrency(p.precio)"></p>
                                    </div>
                                </template>
                                <template x-if="productos.filter(pr => pr.categoria === 'Producto').length === 0">
                                    <p class="col-span-full text-gray-500 text-sm text-center py-4">No hay productos disponibles.</p>
                                </template>
                            </div>
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
                                                      x-text="(c.producto?.nombre || 'Producto') + (c.cantidad > 1 ? ' x' + c.cantidad : '')"></span>
                                                <template x-if="c.origen === 'Promocion'">
                                                    <span class="text-[10px] bg-green-500/20 text-green-400 px-2 py-0.5 rounded-full">Promo</span>
                                                </template>
                                            </div>
                                            <span class="text-[#D4AF37] text-sm font-mono" x-text="c.total === 0 ? 'Gratis' : formatCurrency(c.total)"></span>
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
                                <span class="font-bold" x-text="formatCurrency(saldoPendiente())"></span>
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