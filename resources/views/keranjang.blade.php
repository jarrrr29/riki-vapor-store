@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8 bg-gray-800 rounded-lg shadow-xl text-white">
    <h1 class="text-3xl font-bold mb-6 text-center">Keranjang Belanja Anda</h1>

    <div x-data="{
        cartItems: @json($cartItems), // Ambil item dari backend
        totalPrice: {{ $totalPrice }}, // Ambil total harga dari backend
        orderMessage: '', // Pesan untuk modal pemesanan
        showCheckoutModal: false, // Kontrol tampil/sembunyi modal
        customerName: '',
        customerPhone: '',
        customerAddress: '',

        // Fungsi untuk mengupdate kuantitas item
        updateQuantity(productId, action, newQty = null) {
            let qtyToUse = newQty; // Jika newQty diberikan (dari input)
            if (action === 'increase') qtyToUse = (this.cartItems[productId].quantity || 0) + 1;
            if (action === 'decrease') qtyToUse = (this.cartItems[productId].quantity || 0) - 1;

            if (qtyToUse < 1) qtyToUse = 0; // Pastikan tidak kurang dari 0

            if (qtyToUse === 0) {
                // Jika kuantitas jadi 0, langsung panggil fungsi hapus
                this.removeItem(productId);
                return;
            }

            fetch(`/keranjang/update/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                },
                body: JSON.stringify({ action: (newQty !== null ? 'set' : action), quantity: qtyToUse })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update tampilan keranjang di frontend
                    if (data.itemQuantity > 0) {
                        this.cartItems[productId].quantity = data.itemQuantity;
                    } else {
                        // Jika item dihapus (kuantitas jadi 0)
                        delete this.cartItems[productId];
                    }
                    this.totalPrice = data.totalPrice;
                    // Update cartCount di FAB
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cartCount: data.cartCount } }));
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error updating quantity:', error);
                alert('Terjadi kesalahan saat memperbarui kuantitas.');
            });
        },

        // Fungsi untuk menghapus item
        removeItem(productId) {
            if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                return;
            }
            fetch(`/keranjang/remove/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    delete this.cartItems[productId]; // Hapus item dari tampilan
                    this.totalPrice = data.totalPrice;
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cartCount: data.cartCount } }));
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error removing item:', error);
                alert('Terjadi kesalahan saat menghapus produk.');
            });
        },

        // Fungsi untuk membuka modal Checkout
        openCheckoutModal() {
            this.orderMessage = ''; // Reset pesan modal
            this.customerName = ''; // Reset form
            this.customerPhone = '';
            this.customerAddress = '';
            this.showCheckoutModal = true;
        },

        // Fungsi untuk mengirim pesanan (ke OrderController)
        submitOrder() {
            this.orderMessage = '';
            // Kumpulkan semua item keranjang untuk dikirim ke backend
            const itemsToOrder = Object.values(this.cartItems).map(item => ({
                product_id: item.id,
                quantity: item.quantity,
                // Pastikan item memiliki harga untuk perhitungan total di backend
            }));

            fetch(`/order/store`, { // Route OrderController@store
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                },
                body: JSON.stringify({
                    customer_name: this.customerName,
                    customer_phone: this.customerPhone,
                    customer_address: this.customerAddress,
                    items: itemsToOrder, // Kirim daftar item yang akan dipesan
                    total_price: this.totalPrice // Kirim total harga dari frontend
                })
            })
            .then(response => response.json())
            .then(data => {
                this.orderMessage = data.message;
                if (data.success) {
                    alert('Pesanan Anda berhasil dibuat! Segera diarahkan ke WhatsApp.');
                    // Buat link WhatsApp
                    const waMessage = this.generateWhatsAppMessage(itemsToOrder, this.customerName, this.customerPhone, this.customerAddress, this.totalPrice);
                    window.location.href = `https://wa.me/6281234567890?text=${encodeURIComponent(waMessage)}`; // Ganti nomor HP
                    
                    // Reset keranjang setelah sukses
                    this.cartItems = {};
                    this.totalPrice = 0;
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { cartCount: 0 } })); // Update FAB
                    localStorage.removeItem('cartCount'); // Hapus dari localStorage juga
                    
                    setTimeout(() => { this.showCheckoutModal = false; }, 1000);
                } else {
                    console.error('Gagal membuat pesanan:', data.message);
                }
            })
            .catch(error => {
                console.error('Ada kesalahan:', error);
                this.orderMessage = 'Terjadi kesalahan saat memproses pesanan.';
            });
        },

        // Fungsi untuk membuat pesan WhatsApp
        generateWhatsAppMessage(items, name, phone, address, total) {
            let message = `Halo Riki Vapor, saya ${name} (${phone}) ingin memesan produk berikut:\n\n`;
            items.forEach((item, index) => {
                message += `${index + 1}. ${item.nama} (${item.quantity}x) - Rp ${item.harga.toLocaleString('id-ID')}\n`; // Perbaiki di sini: item.harga mungkin belum ada
            });
            message += `\nTotal Harga: Rp ${total.toLocaleString('id-ID')}`;
            message += `\nAlamat Pengiriman: ${address}`;
            message += `\n\nMohon diproses. Terima kasih!`;
            return message;
        }

    }" class="mb-8">

        @if(empty($cartItems))
            <p class="text-center text-gray-400">Keranjang Anda kosong. Yuk, <a href="{{ url('/pembelian') }}" class="text-green-400 hover:underline">mulai belanja!</a></p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-700 rounded-lg">
                    <thead>
                        <tr class="bg-gray-600 text-gray-300 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Produk</th>
                            <th class="py-3 px-6 text-center">Kuantitas</th>
                            <th class="py-3 px-6 text-center">Harga Satuan</th>
                            <th class="py-3 px-6 text-right">Subtotal</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-200 text-sm font-light">
                        <template x-for="(item, productId) in cartItems" :key="productId">
                            <tr class="border-b border-gray-700 hover:bg-gray-600">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="mr-2">
                                            <img class="w-10 h-10 rounded-full object-cover" :src="'{{ asset('image') }}/' + item.gambar" :alt="item.nama">
                                        </div>
                                        <span x-text="item.nama"></span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <button @click="updateQuantity(productId, 'decrease')" class="bg-gray-600 text-white w-6 h-6 rounded-md hover:bg-gray-500 transition">-</button>
                                        <input type="number" min="1" x-model="item.quantity" @change="updateQuantity(productId, 'set', $event.target.value)"
                                               class="w-12 p-1 text-center bg-gray-800 text-white rounded-md focus:outline-none no-spinner">
                                        <button @click="updateQuantity(productId, 'increase')" class="bg-gray-600 text-white w-6 h-6 rounded-md hover:bg-gray-500 transition">+</button>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    Rp <span x-text="item.harga.toLocaleString('id-ID')"></span>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    Rp <span x-text="(item.harga * item.quantity).toLocaleString('id-ID')"></span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <button @click="removeItem(productId)" class="text-red-500 hover:text-red-700 transition">
                                        <i class="fas fa-times"></i> {{-- Ikon silang --}}
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="text-right mt-6 pr-6">
                <h2 class="text-xl font-bold text-green-400">Total: Rp <span x-text="totalPrice.toLocaleString('id-ID')"></span></h2>
                <button @click="openCheckoutModal()" class="mt-4 bg-green-600 text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-green-700 transition transform hover:scale-105 shadow-lg">
                    Checkout
                </button>
            </div>
        @endif
    </div>

    {{-- MODAL FORM PEMESANAN (di halaman keranjang ini) --}}
    <div x-show="showCheckoutModal" x-cloak x-transition.opacity class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div @click.away="showCheckoutModal = false" class="bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md text-white">
            <h2 class="text-2xl font-bold mb-4 text-center">Form Data Pelanggan</h2>
            <p x-text="orderMessage" x-show="orderMessage" class="text-sm text-center mb-4" :class="orderMessage.includes('berhasil') ? 'text-green-400' : 'text-red-400'"></p>

            <form @submit.prevent="submitOrder()">
                <div class="mb-4">
                    <label for="customerName" class="block text-gray-300 text-sm font-semibold mb-2">Nama Anda:</label>
                    <input type="text" id="customerName" x-model="customerName" required
                           class="w-full p-2 rounded-md bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-green-500">
                </div>
                <div class="mb-4">
                    <label for="customerPhone" class="block text-gray-300 text-sm font-semibold mb-2">Nomor HP:</label>
                    <input type="tel" id="customerPhone" x-model="customerPhone" required
                           class="w-full p-2 rounded-md bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-green-500">
                </div>
                <div class="mb-6">
                    <label for="customerAddress" class="block text-gray-300 text-sm font-semibold mb-2">Alamat Lengkap:</label>
                    <textarea id="customerAddress" x-model="customerAddress" required rows="3"
                              class="w-full p-2 rounded-md bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-green-500"></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" @click="showCheckoutModal = false; resetCartAndOrderForm()"
                            class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-500 transition">Batal (Reset Keranjang)</button>
                    <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">Pesan Sekarang</button>
                </div>
            </form>
        </div>
    </div>
    @endsection

    {{-- Script untuk CSS tambahan & Splide.js (tetap di sini) --}}
    @push('scripts')
    <script>
        // CSS untuk menghilangkan tombol spinner (tetap ada)
        const style = document.createElement('style');
        style.innerHTML = `
            .no-spinner::-webkit-outer-spin-button,
            .no-spinner::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            .no-spinner {
                -moz-appearance: textfield; /* Firefox */
            }
        `;
        document.head.appendChild(style);

        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Splide.js (jika ada di halaman ini)
            // ... (kode Splide.js jika nanti ada di halaman keranjang)

            // Pastikan event listeners untuk quantity +/- dan remove ada di sini
            // (sudah di handle langsung di x-data di atas)
        });
    </script>
    @endpush
