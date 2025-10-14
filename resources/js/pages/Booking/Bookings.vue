<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';
    import type { BreadcrumbItem, Booking } from '@/types';
    import { Head, router } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import { Label } from '@/components/ui/label';
    import { Button } from '@/components/ui/button';
    import { TrashIcon } from 'lucide-vue-next';
    import { toast } from 'vue-sonner'
    import { route } from 'ziggy-js';
    import {
      AlertDialog,
      AlertDialogAction,
      AlertDialogCancel,
      AlertDialogContent,
      AlertDialogDescription,
      AlertDialogFooter,
      AlertDialogHeader,
      AlertDialogTitle,
    } from '@/components/ui/alert-dialog'

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

    const showDialog = ref(false)
    const bookingToDelete = ref<Booking | null>(null)

    const confirmDelete = (booking: Booking) => {
      bookingToDelete.value = booking
      showDialog.value = true
    }


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

    const isDeleting = ref(false)

    const handleDelete = (id: number) => {
        if (!bookingToDelete.value) return
        isDeleting.value = true
        router.delete(route('bookings.destroy', { id: bookingToDelete.value.id }), {
          preserveState: false,
          onSuccess: () => {
            toast.success('Booking Deleted!', {
              description: `Bookings for ${bookingToDelete.value!.customer_name} has been deleted successfully.`,
            })
            showDialog.value = false
            bookingToDelete.value = null
          },
          onError: () => {
            toast.error('Deletion Failed!', {
              description: 'Something went wrong while deleting the booking.',
            })
          },
          onFinish: () => {
            isDeleting.value = false
          }
        })
    }
</script>

<template>
  <Head title="Bookings" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <Table>
        <TableCaption>
            <p v-if="props.bookings.data.length > 0">A list of customer bookings.</p>
            <p v-else>Empty Bookings.</p>
        </TableCaption>
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
            <TableHead class="flex justify-center">
                <Label>Action</Label>
            </TableHead>
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
            <TableCell>
                <Button class="bg-red-700 hover:bg-red-500"
                :disabled="isDeleting"
                @click="confirmDelete(booking)">
                  <TrashIcon />Delete
                </Button>
            </TableCell>
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
      <AlertDialog :open="showDialog" @update:open="showDialog = $event">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Delete Booking?</AlertDialogTitle>
            <AlertDialogDescription>
              Are you sure you want to delete
              <span class="font-semibold">{{ bookingToDelete?.customer_name }}</span>’s
              booking? This action cannot be undone.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel @click="showDialog = false">Cancel</AlertDialogCancel>
            <AlertDialogAction
              class="bg-red-600 text-white hover:bg-red-700"
              @click="handleDelete"
            >
              Yes, Delete!
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  </AppLayout>
</template>
