<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';
    import type { BreadcrumbItem, Schedule, Seat } from '@/types';
    import { Head } from '@inertiajs/vue3';
    import { Label } from '@/components/ui/label';
    import { Input } from '@/components/ui/input';
    import { Button } from '@/components/ui/button';
    import { usePage, useForm } from '@inertiajs/vue3';
    import { TicketCheckIcon } from 'lucide-vue-next';
    import { toast } from 'vue-sonner';
    import { computed } from 'vue';
import { route } from 'ziggy-js';

    const breadcrumbs: BreadcrumbItem[] = [
        { 
            title: 'Schedules', 
            href: '/schedules' 
        },
        { 
            title: 'Book Movie', 
            href: '/schedules/book' 
        },
    ];

    const props = defineProps<{
        schedule: Schedule,
        seats: Seat[],
        bookedSeatIds: number[],
    }>()

    const form = useForm({
        schedule_id: props.schedule.id,
        customer_name: '',
        seat_id: [] as number[],
    })

    // Group seats by row (A, B, C, D, E, F)
    const seatsByRow = computed(() => {
        if (!props.seats || !Array.isArray(props.seats)) {
            return {};
        }
        
        const rows: Record<string, Seat[]> = {};
        
        props.seats.forEach(seat => {
            const row = seat.seat_number.charAt(0); // Get first letter (A, B, C, etc.)
            if (!rows[row]) {
                rows[row] = [];
            }
            rows[row].push(seat);
        });

        // Sort seats within each row by number
        Object.keys(rows).forEach(row => {
            rows[row].sort((a, b) => {
                const numA = parseInt(a.seat_number.slice(1));
                const numB = parseInt(b.seat_number.slice(1));
                return numA - numB;
            });
        });

        return rows;
    });

    // Get sorted row letters
    const rowLetters = computed(() => {
        return Object.keys(seatsByRow.value).sort();
    });

    // Toggle seat selection
    const toggleSeat = (seatId: number) => {
        const index = form.seat_id.indexOf(seatId);
        if (index > -1) {
            form.seat_id.splice(index, 1);
        } else {
            form.seat_id.push(seatId);
        }
    };

    // Check if seat is selected
    const isSeatSelected = (seatId: number) => {
        return form.seat_id.includes(seatId);
    };

    // Check if seat is booked
    const isSeatBooked = (seatId: number) => {
        return props.bookedSeatIds.includes(seatId);
    };

    const handleSubmit = () => {
        form.post(route('bookings.store'), {
            preserveState: false,
            onSuccess: () => {
                toast.success('Booking Successful!', {
                    description: `You have booked ${form.seat_id.length} seat(s)`,
                })
            },
            onError: () => {
                if (form.errors.seat_id) {
                    toast.error('Seat Needed!', {
                    description: form.errors.seat_id,
                    })
                }
                if (form.errors.customer_name) {
                    toast.error('Name Required!', {
                    description: form.errors.customer_name,
                    })
                }
            }
        })
    }

    function formatDate(dateString: string) {
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', {
            weekday: 'short',   // "Tue"
            year: 'numeric',    // "2025"
            month: 'long',      // "October"
            day: 'numeric',     // "14"
        })
    }

    
</script>

<template>
    <Head title="Book a Movie" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 ml-15 flex gap-8">
            <!-- Left side - Movie details -->
            <div class="w-[400px]">
                <div class="mb-6">
                    <Label class="italic text-md text-gray-600">Movie</Label>
                    <Label class="block text-lg font-bold">{{ props.schedule.movie.title }}</Label>
                </div>

                <div class="mb-6">
                    <Label class="italic text-md text-gray-600">Cinema</Label>
                    <Label class="block text-lg font-bold">{{ props.schedule.cinema.name }}</Label>
                </div>

                <div class="mb-6">
                    <Label class="italic text-md text-gray-600">City</Label>
                    <Label class="block text-lg font-bold">{{ props.schedule.city.name }}</Label>
                </div>

                <div class="mb-6">
                    <Label class="italic text-md text-gray-600">Show Date</Label>
                    <Label class="block text-lg font-bold">{{ formatDate(props.schedule.show_date) }}</Label>
                </div>

                <div class="mb-6">
                    <Label class="italic text-md text-gray-600">Timeslot</Label>
                    <Label class="block text-lg font-bold">
                        {{ props.schedule.timeslot.start_time }} - {{ props.schedule.timeslot.end_time }}
                    </Label>
                </div>

                <div class="mb-4">
                    <Label class="italic text-md text-gray-600">Customer Name</Label>
                    <Input v-model="form.customer_name" placeholder="Enter your name" class="mt-2" />
                    <div class="text-xs text-red-600" v-if="form.errors.customer_name">{{ form.errors.customer_name }}</div>
                </div>

                <div class="mb-4">
                    <Label class="italic text-md text-gray-600">Selected Seats</Label>
                    <div class="mt-2 p-3 bg-gray-100 rounded-md min-h-[40px]">
                        <span v-if="form.seat_id.length === 0" class="text-gray-400">No seats selected</span>
                        <span v-else class="font-semibold">
                            {{ form.seat_id.length }} seat(s) selected
                        </span>
                    </div>
                </div>

                <Button class="w-full mt-6" 
                @click="handleSubmit" 
                :disabled="form.processing">
                    <TicketCheckIcon />
                    Confirm Booking
                </Button>
            </div>

            <!-- Right side - Seat selection -->
            <div class="flex-1 mt-10">
                <div class="flex flex-col items-center">
                    <Label class="italic text-md mb-4 text-gray-600">Select Your Seats</Label>
                    
                    <!-- Screen -->
                    <div class="w-full max-w-[800px] bg-gray-300 h-[50px] flex justify-center items-center rounded-lg mb-8 shadow-md">
                        <span class="font-bold text-gray-700">SCREEN</span>
                    </div>

                    <!-- Seats Grid -->
                    <div class="space-y-3">
                        <div v-for="row in rowLetters" :key="row" class="flex items-center gap-2">
                            <!-- Row Letter -->
                            <div class="w-8 text-center font-bold text-gray-700">
                                {{ row }}
                            </div>

                            <!-- Seats in this row -->
                            <div class="flex gap-2">
                                <button
                                    v-for="seat in seatsByRow[row]"
                                    :key="seat.id"
                                    @click="toggleSeat(seat.id)"
                                    :disabled="isSeatBooked(seat.id)"
                                    :class="[
                                        'w-10 h-10 rounded-md border-2 transition-all duration-200 font-semibold text-sm',
                                        isSeatBooked(seat.id)
                                            ? 'bg-red-200 border-red-400 text-red-700 cursor-not-allowed opacity-60'
                                            : isSeatSelected(seat.id)
                                            ? 'bg-blue-500 border-blue-600 text-white hover:bg-blue-600'
                                            : 'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200 hover:border-gray-400'
                                    ]"
                                    :title="isSeatBooked(seat.id) ? 'Already Booked' : seat.seat_number"
                                >
                                    {{ seat.seat_number.slice(1) }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="flex gap-6 mt-8 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gray-100 border-2 border-gray-300 rounded-md"></div>
                            <span>Available</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-500 border-2 border-blue-600 rounded-md"></div>
                            <span>Selected</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-red-200 border-2 border-red-400 rounded-md"></div>
                            <span>Booked</span>
                        </div>
                    </div>
                    <div class="text-xs text-red-600 mt-4" v-if="form.errors.seat_id">{{ form.errors.seat_id }}</div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>