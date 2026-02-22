<template>
    <div class="cinema-seat-layout">
        <!-- Layar Bioskop -->
        <div class="screen-container">
            <div class="screen">LAYAR BIOSKOP</div>
            <div class="screen-curve"></div>
        </div>

        <!-- Layout Kursi -->
        <div class="seats-container">
            <!-- Baris kursi dengan nomor -->
            <div v-for="row in seatRows" :key="row.rowLetter" class="seat-row">
                <!-- Nomor baris -->
                <div class="row-label">{{ row.rowLetter }}</div>

                <!-- Kursi dalam baris -->
                <div class="seats-in-row">
                    <div v-for="seat in row.seats" :key="seat.id" class="seat-wrapper">
                        <button @click="toggleSeat(seat)" :disabled="seat.status === 'booked'" :class="[
                            'seat',
                            `seat-${seat.type}`,
                            {
                                'seat-selected': seat.status === 'selected',
                                'seat-booked': seat.status === 'booked',
                                'seat-available': seat.status === 'available',
                                'seat-disabled': seat.status === 'disabled'
                            }
                        ]" :title="getSeatTitle(seat)">
                            <span v-if="seat.type === 'couple'" class="seat-icon">❤️</span>
                            <span v-else class="seat-number">{{ seat.number }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend dan Informasi -->
        <div class="info-panel">
            <div class="seat-legend">
                <div class="legend-item">
                    <div class="legend-color seat-available"></div>
                    <span>Tersedia</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color seat-selected"></div>
                    <span>Dipilih</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color seat-booked"></div>
                    <span>Terisi</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color seat-couple"></div>
                    <span>Kursi Couple</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color seat-disabled"></div>
                    <span>Rusak/Tidak Tersedia</span>
                </div>
            </div>

            <!-- Ringkasan Pemesanan -->
            <div class="booking-summary" v-if="selectedSeats.length > 0">
                <h3>Ringkasan Pemesanan</h3>
                <p>Kursi dipilih: {{ selectedSeatsFormatted }}</p>
                <p>Total: {{ selectedSeats.length }} kursi</p>
                <p class="total-price">Harga: Rp {{ formatPrice(totalPrice) }}</p>
                <button @click="confirmBooking" class="btn-book">
                    Lanjutkan Pemesanan
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SeatLayout',

    props: {
        // Harga per kursi (default: Rp 50,000)
        basePrice: {
            type: Number,
            default: 50000
        },
        // Harga kursi couple (biasanya lebih mahal)
        couplePrice: {
            type: Number,
            default: 100000
        },
        // Jumlah baris
        rowsCount: {
            type: Number,
            default: 8
        },
        // Jumlah kursi per baris
        seatsPerRow: {
            type: Number,
            default: 12
        }
    },

    data() {
        return {
            seatRows: [],
            selectedSeats: []
        }
    },

    computed: {
        // Format kursi yang dipilih (contoh: A1, A2, B3)
        selectedSeatsFormatted() {
            return this.selectedSeats
                .map(seat => `${seat.row}${seat.number}`)
                .join(', ')
        },

        // Total harga berdasarkan tipe kursi
        totalPrice() {
            return this.selectedSeats.reduce((total, seat) => {
                if (seat.type === 'couple') {
                    return total + this.couplePrice
                }
                return total + this.basePrice
            }, 0)
        }
    },

    mounted() {
        this.generateSeats()
    },

    methods: {
        // Generate layout kursi
        generateSeats() {
            const rows = []
            const rowLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'

            for (let i = 0; i < this.rowsCount; i++) {
                const rowLetter = rowLetters[i]
                const seats = []

                for (let j = 1; j <= this.seatsPerRow; j++) {
                    // Tentukan tipe kursi (beberapa kursi khusus di tengah sebagai kursi couple)
                    let type = 'regular'
                    const seatNumber = j

                    // Kursi couple di tengah (misal posisi 5-6 dan 7-8)
                    if (rowLetter >= 'D' && rowLetter <= 'G') {
                        if (seatNumber === 5 || seatNumber === 6 || seatNumber === 7 || seatNumber === 8) {
                            type = 'couple'
                        }
                    }

                    // Random status untuk demo
                    const random = Math.random()
                    let status = 'available'

                    if (random < 0.2) {
                        status = 'booked'
                    } else if (random < 0.25) {
                        status = 'disabled'
                    }

                    seats.push({
                        id: `${rowLetter}${j}`,
                        row: rowLetter,
                        number: j,
                        type: type,
                        status: status
                    })
                }

                rows.push({
                    rowLetter: rowLetter,
                    seats: seats
                })
            }

            this.seatRows = rows
        },

        // Toggle pemilihan kursi
        toggleSeat(seat) {
            if (seat.status === 'booked' || seat.status === 'disabled') {
                return
            }

            if (seat.status === 'selected') {
                // Hapus dari selected
                seat.status = 'available'
                this.selectedSeats = this.selectedSeats.filter(s => s.id !== seat.id)
            } else {
                // Tambah ke selected
                seat.status = 'selected'
                this.selectedSeats.push({
                    id: seat.id,
                    row: seat.row,
                    number: seat.number,
                    type: seat.type
                })
            }
        },

        // Dapatkan title untuk tooltip
        getSeatTitle(seat) {
            if (seat.status === 'booked') return 'Kursi sudah dipesan'
            if (seat.status === 'disabled') return 'Kursi tidak tersedia'
            if (seat.status === 'selected') return 'Kursi dipilih'
            if (seat.type === 'couple') return 'Kursi couple - Rp ' + this.formatPrice(this.couplePrice)
            return `Kursi ${seat.row}${seat.number} - Rp ${this.formatPrice(this.basePrice)}`
        },

        // Format harga
        formatPrice(price) {
            return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
        },

        // Konfirmasi pemesanan
        confirmBooking() {
            if (this.selectedSeats.length === 0) {
                alert('Pilih kursi terlebih dahulu!')
                return
            }

            // Emit event ke parent component
            this.$emit('booking-confirmed', {
                seats: this.selectedSeats,
                totalPrice: this.totalPrice,
                seatsFormatted: this.selectedSeatsFormatted
            })

            alert(`Booking confirmed for seats: ${this.selectedSeatsFormatted}`)
        }
    }
}
</script>

<style scoped>
.cinema-seat-layout {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

/* Screen Styles */
.screen-container {
    text-align: center;
    margin-bottom: 50px;
    perspective: 300px;
}

.screen {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    border-radius: 10px 10px 0 0;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
}

.screen-curve {
    height: 20px;
    background: linear-gradient(to bottom, #764ba2, transparent);
    border-radius: 0 0 50% 50%;
    transform: rotateX(5deg);
    margin-top: -5px;
}

/* Seats Layout */
.seats-container {
    margin: 30px 0;
}

.seat-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.row-label {
    width: 40px;
    font-weight: bold;
    font-size: 18px;
    color: #333;
    text-align: center;
}

.seats-in-row {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    flex: 1;
}

.seat-wrapper {
    flex: 0 0 auto;
}

/* Seat Button Styles */
.seat {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 10px 10px 5px 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.seat:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Seat Types */
.seat-available {
    background: #4CAF50;
    color: white;
}

.seat-selected {
    background: #2196F3;
    color: white;
    border: 2px solid #ffd700;
}

.seat-booked {
    background: #f44336;
    color: white;
    cursor: not-allowed;
    opacity: 0.7;
}

.seat-disabled {
    background: #9e9e9e;
    color: white;
    cursor: not-allowed;
    opacity: 0.5;
}

/* Couple seat special styling */
.seat-couple {
    background: #ff9800;
    position: relative;
    width: 45px;
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
}

.seat-couple .seat-icon {
    font-size: 16px;
}

/* Seat number */
.seat-number {
    font-weight: bold;
}

/* Info Panel */
.info-panel {
    margin-top: 50px;
    padding: 20px;
    background: #f5f5f5;
    border-radius: 10px;
}

.seat-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 25px;
    height: 25px;
    border-radius: 8px 8px 3px 3px;
}

.seat-couple.legend-color {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
}

/* Booking Summary */
.booking-summary {
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.booking-summary h3 {
    margin-top: 0;
    color: #333;
}

.total-price {
    font-size: 18px;
    font-weight: bold;
    color: #4CAF50;
}

.btn-book {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-book:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
    .seat {
        width: 30px;
        height: 30px;
        font-size: 10px;
    }

    .seat-couple {
        width: 35px;
    }

    .row-label {
        width: 30px;
        font-size: 14px;
    }

    .seat-legend {
        gap: 10px;
    }

    .legend-item {
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .seat {
        width: 25px;
        height: 25px;
    }

    .seat-couple {
        width: 30px;
    }

    .seats-in-row {
        gap: 3px;
    }
}
</style>