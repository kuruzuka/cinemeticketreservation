<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue'
    import type { BreadcrumbItem, Booking } from '@/types';
    import { 
        Table, TableBody, TableCaption, TableCell, 
        TableRow, TableHead, TableHeader, TableEmpty
    } from '@/components/ui/table';
    import { usePage } from '@inertiajs/vue3';
    import { watch } from 'vue';
    import { Toaster } from '@/components/ui/sonner';
    import { toast } from 'vue-sonner';
    import 'vue-sonner/style.css'

    const breadcrumbs : BreadcrumbItem[] = [
        {
            title: 'Bookings',
            href: '/bookings'
        },
    ];
    
    defineProps<{bookings: Booking[]}>()

    const page = usePage()
    const flash = page.props.flash as Record<string, string> | undefined

    if ( flash?.['toast.success'] ) {
        toast.success(flash['toast.success'])
    }

    watch(
  () => page.props.flash,
  (newFlash) => {
    if (newFlash?.['toast.success']) {
      toast.success(newFlash['toast.success'])
    }
    if (newFlash?.['toast.error']) {
      toast.error(newFlash['toast.error'])
    }
  },
  { immediate: true } // also run on mount if flash exists
)

</script>

<template>
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <Toaster />
        <div class="p-4">
            <div>
                <Table>
                    <TableCaption>
                        <div v-if="bookings.length === 0">
                            <p class="italic">No bookings.</p>
                        </div>
                        <div v-else>
                            <p class="italic">Booking list.</p>
                        </div>
                    </TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">
                                ID
                            </TableHead>
                            <TableHead>Customer</TableHead>
                            <TableHead>Movie</TableHead>
                            <TableHead>Cinema</TableHead>
                            <TableHead>Seats</TableHead>
                            <TableHead>Timeslot</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="booking in bookings" :key="booking.id">
                            <TableCell class="font-medium">
                                {{ booking.id }}
                            </TableCell>
                            <TableCell>
                                {{ booking.customer_fname + " " + booking.customer_midname + " " + booking.customer_lname }}
                            </TableCell>
                            <TableCell>{{ booking.title }}</TableCell>
                            <TableCell>{{ booking.cinema }}</TableCell>
                            <TableCell>{{ booking.seats }}</TableCell>
                            <TableCell>{{ booking.timeslot }}</TableCell>
                        </TableRow>
                    </TableBody>
                    
                </Table>
            </div>
        </div>

    </AppLayout>

</template>