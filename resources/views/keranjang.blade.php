@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8 bg-gray-800 rounded-lg shadow-xl text-white">
    <h1 class="text-3xl font-bold mb-6 text-center">Keranjang Belanja Anda</h1>

    @if(count($cartItems) > 0)
        <div x-data="{
            cartItems: {{ Js::from($cartItems) }},
            totalPrice: {{ $totalPrice }},
            showCheckoutModal: false,
            customerName: '',
            customerPhone: '',
            customerAddress: '',

            // Logika untuk mengunci scroll body saat modal aktif
            init() {
                this.$watch('showCheckoutModal', value => {
                    if (value) {
                        document.body.classList.add('overflow-y-hidden');
                    } else {
                        document.body.classList.remove('overflow-y-hidden');
                    }
                });
            },

            generateReceipt() {
                let receipt = '--- STRUK PESANAN ---\n\n';
                receipt += `Nama: ${this.customerName}\n`;
                receipt += `No. HP: ${this.customerPhone}\n`;
                receipt += `Alamat: ${this.customerAddress}\n\n`;
                receipt += 'Detail Pesanan:\n';
                for (let id in this.cartItems) {
                    const item = this.cartItems[id];
                    receipt += `- ${item.nama} (x${item.quantity})\n`;
                }
                receipt += '\n------------------\n';
                receipt += `TOTAL: Rp ${this.totalPrice.toLocaleString('id-ID')}`;
                return receipt;
            },

            sendWhatsAppOrder() {
                if (!this.customerName || !this.customerPhone || !this.customerAddress) {
                    alert('Harap lengkapi semua data pelanggan terlebih dahulu.');
                    return;
                }
                
                const introMessage = 'Halo Riki Vapor, saya ingin pesan ini:\n\n';
                const receipt = this.generateReceipt();
                const finalMessage = introMessage + receipt;

                const waLink = `https://wa.me/6285272830575?text=${encodeURIComponent(finalMessage)}`;

                fetch('/keranjang/clear', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(() => {
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cartCount: 0 } }));
                });

                window.open(waLink, '_blank');

                this.showCheckoutModal = false;
                this.cartItems = {};
                window.location.reload();
            },
            
            recalculateTotal() {
                let newTotal = 0;
                for (let id in this.cartItems) {
                    newTotal += this.cartItems[id].harga * this.cartItems[id].quantity;
                }
                this.totalPrice = newTotal;
            },
            updateQuantity(productId, action) {
                let item = this.cartItems[productId];
                if (!item) return;
                if (action === 'increase') item.quantity++;
                if (action === 'decrease') item.quantity--;
                if (item.quantity <= 0) return this.removeItem(productId);
                
                this.recalculateTotal();

                fetch(`/keranjang/update/${productId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ quantity: item.quantity, action: 'set' })
                }).then(res => res.json()).then(data => {
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cartCount: data.cartCount } }));
                });
            },
            removeItem(productId) {
                if (!confirm('Anda yakin ingin menghapus produk ini?')) return;
                
                delete this.cartItems[productId];
                this.recalculateTotal();

                fetch(`/keranjang/remove/${productId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(res => res.json()).then(data => {
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cartCount: data.cartCount } }));
                    if (Object.keys(this.cartItems).length === 0) {
                        window.location.reload();
                    }
                });
            },
            openCheckoutModal() {
                this.showCheckoutModal = true;
            }
        }" x-init="init()">
            <template x-if="Object.keys(cartItems).length > 0">
                <div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-700 rounded-lg">
                            <thead>
                                <tr class="bg-gray-600 text-gray-300 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Produk</th>
                                    <th class="py-3 px-6 text-center">Kuantitas</th>
                                    <th class="py-3 px-6 text-center">Harga</th>
                                    <th class="py-3 px-6 text-right">Subtotal</th>
                                    <th class="py-3 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-200 text-sm font-light">
                                <template x-for="(item, productId) in cartItems" :key="productId">
                                    <tr class="border-b border-gray-700 hover:bg-gray-600">
                                        <td class="py-3 px-6 text-left whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="w-10 h-10 rounded-full object-cover mr-2" :src="'/image/' + item.gambar" :alt="item.nama">
                                                <span x-text="item.nama"></span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex items-center justify-center">
                                                <button @click="updateQuantity(productId, 'decrease')" class="px-2">-</button>
                                                <span x-text="item.quantity" class="px-2"></span>
                                                <button @click="updateQuantity(productId, 'increase')" class="px-2">+</button>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-center" x-text="'Rp ' + item.harga.toLocaleString('id-ID')"></td>
                                        <td class="py-3 px-6 text-right" x-text="'Rp ' + (item.harga * item.quantity).toLocaleString('id-ID')"></td>
                                        <td class="py-3 px-6 text-center">
                                            <button @click="removeItem(productId)" class="text-red-500 hover:text-red-700">Hapus</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right mt-6 pr-6">
                        <h2 class="text-xl font-bold text-green-400">Total: Rp <span x-text="totalPrice.toLocaleString('id-ID')"></span></h2>
                        <button @click="openCheckoutModal()" class="mt-4 bg-green-600 text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-green-700">Checkout</button>
                    </div>
                </div>
            </template>
            
            <template x-if="Object.keys(cartItems).length === 0">
                <div class="text-center py-16">
                    <p class="text-gray-400 text-xl">Keranjang Anda sekarang kosong.</p>
                    <a href="{{ url('/pembelian') }}" class="mt-4 inline-block bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full">Kembali Belanja</a>
                </div>
            </template>
            
            <div x-show="showCheckoutModal" x-cloak x-transition.opacity class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
                <div @click.away="showCheckoutModal = false" class="bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg text-white max-h-[90vh] overflow-y-auto">
                    <form @submit.prevent="sendWhatsAppOrder()">
                        <h2 class="text-2xl font-bold mb-4 text-center">Data & Struk Pesanan</h2>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm">Nama Anda:</label>
                            <input type="text" x-model="customerName" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-sm">
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm">Nomor HP (WhatsApp):</label>
                            <input type="tel" x-model="customerPhone" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-sm">
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm">Alamat Lengkap:</label>
                            <textarea x-model="customerAddress" required rows="2" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-sm"></textarea>
                        </div>
                        <div class="mb-6">
                            <label class="block mb-2 font-semibold">Struk Pesanan (Akan dikirim ke WA):</label>
                            <div class="bg-gray-900 p-3 rounded border border-gray-600 text-xs whitespace-pre-wrap" x-text="generateReceipt()"></div>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="button" @click="showCheckoutModal = false" class="bg-gray-500 px-4 py-2 rounded">Batal</button>
                            <button type="submit" class="bg-green-600 px-4 py-2 rounded">Kirim Pesanan ke WhatsApp</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-16">
            <p class="text-gray-400 text-xl">Keranjang Anda kosong.</p>
            <a href="{{ url('/pembelian') }}" class="mt-4 inline-block bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush