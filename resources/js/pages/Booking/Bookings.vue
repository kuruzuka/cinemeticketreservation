<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';
    import type { BreadcrumbItem, Booking } from '@/types';
    import { Head, router } from '@inertiajs/vue3';
    import { ref } from 'vue';

    import {
        Table,
        TableBody,
        TableCaption,
        TableCell,
        TableHead,
        TableHeader,
        TableRow,
    } from "@/components/ui/table"

    import {
        Pagination,
        PaginationContent,
        PaginationEllipsis,
        PaginationItem,
        PaginationNext,
        PaginationPrevious,
    } from '@/components/ui/pagination';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Bookings', href: '/bookings' },
    ];

    const props = defineProps<{
        bookings: {
            data: Booking[],
            current_page: number,
            last_page: number,
            per_page: number,
            total: number,
        }
    }>()

    // reactive pagination state
    const currentPage = ref(props.bookings.current_page)
    const itemsPerPage = props.bookings.per_page

    function goToPage(page: number) {
        if (page !== currentPage.value) {
            router.visit(`?page=${page}`, { preserveScroll: true })
        }
    }

    function formatDate(dateString: string) {
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', {
            weekday: 'short',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        })
    }
</script>

<template>
  <Head title="Bookings" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <Table>
        <TableCaption>A list of customer bookings.</TableCaption>
        <TableHeader>
          <TableRow>
            <TableHead>Customer</TableHead>
            <TableHead>Movie</TableHead>
            <TableHead>City</TableHead>
            <TableHead>Cinema</TableHead>
            <TableHead>Show Date</TableHead>
            <TableHead>Timeslot</TableHead>
            <TableHead>Seats</TableHead>
            <TableHead>Date Booked</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="booking in bookings.data" :key="booking.id">
            <TableCell>{{ booking.customer_name }}</TableCell>
            <TableCell>{{ booking.schedule.movie.title }}</TableCell>
            <TableCell>{{ booking.schedule.city.name }}</TableCell>
            <TableCell>{{ booking.schedule.cinema.name }}</TableCell>
            <TableCell>{{ formatDate(booking.schedule.show_date) }}</TableCell>
            <TableCell>
              {{ booking.schedule.timeslot.start_time }} -
              {{ booking.schedule.timeslot.end_time }}
            </TableCell>
            <TableCell>{{ booking.seats.join(', ') }}</TableCell>
            <TableCell>{{ formatDate(booking.created_at as string) }}</TableCell>
          </TableRow>
        </TableBody>
      </Table>

      <!-- PAGINATION -->
      <div class="mt-4 flex justify-center">
        <Pagination
          v-slot="{ page }"
          :items-per-page="itemsPerPage"
          :total="bookings.total"
          :default-page="currentPage"
          @update:page="val => goToPage(val)"
        >
          <PaginationContent v-slot="{ items }">
            <PaginationPrevious
              @click="goToPage(currentPage - 1)"
              :disabled="currentPage === 1"
            />
            <template v-for="(item, index) in items" :key="index">
              <PaginationItem
                v-if="item.type === 'page'"
                :value="item.value"
                :is-active="item.value === currentPage"
                @click="goToPage(item.value)"
              >
                {{ item.value }}
              </PaginationItem>
            </template>
            <PaginationEllipsis v-if="bookings.last_page > 7" :index="4" />
            <PaginationNext
              @click="goToPage(currentPage + 1)"
              :disabled="currentPage === bookings.last_page"
            />
          </PaginationContent>
        </Pagination>
      </div>
    </div>
  </AppLayout>
</template>
