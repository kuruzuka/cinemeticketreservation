<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';
    import { type BreadcrumbItem, Movie } from '@/types';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Button } from '@/components/ui/button';
    import { useForm } from '@inertiajs/vue3';
    import { route } from 'ziggy-js';

    // --- Breadcrumbs ---
    const breadcrumbs: BreadcrumbItem[] = [
        { 
            title: 'Movies', 
            href: '/movies' 
        },
        {
            title: 'Booking',
            href: 'movies/book'
        }
    ];

    const props = defineProps<{ movie: Movie }>();

    const form = useForm({
        title: props.movie.title,
        cinema: props.movie.cinema,
        timeslot: props.movie.start + " - " + props.movie.end,
        customer_fname: '',
        customer_midname: '',
        customer_lname: '',
        seats: [] as string[],  // initialize as string array
    });

    function generateSeats(rows: string[], seatsPerRow: number): string[] {
        const seats: string[] = [];
        for (const row of rows) {
            for (let seatNumber = 1; seatNumber <= seatsPerRow; seatNumber++) {
                seats.push(`${row}${seatNumber}`);
            }
        }
        return seats;
    }

    function chunkArray<T>(arr: T[], size: number): T[][] {
        const chunks: T[][] = [];
        for (let i = 0; i < arr.length; i += size) {
            chunks.push(arr.slice(i, i + size));
        }
        return chunks;
    }

    const rows = ['A', 'B', 'C', 'D'];
    const seatsPerRow = 9;
    const seats = generateSeats(rows, seatsPerRow);
    const seatRows = chunkArray(seats, seatsPerRow);

    const bookMovie = () => {
        console.log(form.seats)
        form.post(route('booking.store'));
    };
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center p-4">
            <form @submit.prevent="bookMovie" class="w-[500px]">
                <div>
                    <Label>Movie:</Label>
                    <Label class="text-lg font-bold mt-2 mb-5">{{ form.title }}</Label>

                    <Label class="mt-10">Cinema</Label>
                    <Label class="text-lg font-bold mt-2 mb-5">{{ form.cinema }}</Label>

                    <Label class="mt-10">Timeslot</Label>
                    <Label class="text-lg font-bold mt-2 mb-5">{{ form.timeslot }}</Label>
                </div>

                <div class="flex flex-col items-center mb-10">
                    <Label class="text-md mt-5">Seat</Label>
                    <div class="bg-gray-200 h-[40px] w-[500px] flex items-center justify-center my-5 rounded-lg">
                        <p>SCREEN</p>
                    </div>
                    <!-- Loop over seat rows -->
                    <div v-for="(row, rowIndex) in seatRows" :key="rowIndex" class="flex space-x-4 mb-4">
                        <!-- Loop over each seat in row -->
                        <div v-for="seat in row" :key="seat" class="flex space-x-1 items-center">
                            <input
                                type="checkbox"
                                :id="seat"
                                :value="seat"
                                v-model="form.seats"
                            />
                            <Label :for="seat">{{ seat }}</Label>
                        </div>
                    </div>
                </div>

                <div>
                    <Label class="text-md mb-2">Customer Information</Label>
                    <Input v-model="form.customer_fname" type="text" placeholder="First Name" class="w-full mb-3" />
                    <Input v-model="form.customer_midname" type="text" placeholder="Middle Name" class="w-full mb-3" />
                    <Input v-model="form.customer_lname" type="text" placeholder="Last Name" class="w-full mb-3" />
                    <Button class="w-full mt-4" type="submit">Book</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
