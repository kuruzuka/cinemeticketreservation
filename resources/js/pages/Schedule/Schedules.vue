<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';
    import type { BreadcrumbItem, Schedule } from '@/types';
    import { ref, computed, onMounted, watch } from 'vue';
    import { Head } from '@inertiajs/vue3';
    import { defineProps } from 'vue';
    import { Button } from '@/components/ui/button';
    import { Label } from '@/components/ui/label';
    import { Input } from '@/components/ui/input';
    import { TicketIcon } from 'lucide-vue-next';
    import {
        Select,
        SelectContent,
        SelectGroup,
        SelectItem,
        SelectLabel,
        SelectTrigger,
        SelectValue,
    } from "@/components/ui/select"
    import {
        Table,
        TableBody,
        TableCaption,
        TableCell,
        TableHead,
        TableHeader,
        TableRow,
    } from '@/components/ui/table'
    import { 
        Pagination,
        PaginationContent,
        PaginationEllipsis,
        PaginationItem,
        PaginationNext,
        PaginationPrevious,
    } from '@/components/ui/pagination';
    import { route } from 'ziggy-js';

    const breadcrumbs: BreadcrumbItem[] = [
        { 
            title: 'Schedules', 
            href: '/schedules' 
        },
    ];

    const props = defineProps<{
        schedules: Schedule[]
    }>()

    // Reactive states
    const data = ref<Schedule[]>([])
    const originalSorted = ref<Schedule[]>([])
    const improvedSorted = ref<Schedule[]>([])
    const currentPage = ref(1)
    const itemsPerPage = 10

    const originalTime = ref(0)
    const improvedTime = ref(0)

    const searchQuery = ref('')

    const searchedData = computed(() => {
        const query = searchQuery.value.toLowerCase().trim()

        if (!query) return data.value

        return data.value.filter(s => [
            s.movie.title,
            s.movie.genre.name,
            s.city.name,
            s.cinema.name,
            s.show_date,
            s.timeslot.start_time + ' - ' + s.timeslot.end_time
        ].some(field => field.toLowerCase().includes(query)))
    })


    const sortKey = ref<'title' | 'city' | 'timeslot' | 'date'>('title')
    function getSortValue(schedule: Schedule, key: string): string {
        switch (key) {
            case 'title':
                return schedule.movie.title
            case 'city':
                return schedule.city.name
            case 'timeslot':
                return schedule.timeslot.start_time
            case 'date':
                return schedule.show_date
            default:
                return ''
        }
    }

    // ===== ORIGINAL MERGE SORT =====
    function originalMergeSort(arr: Schedule[], left: number, right: number) {
        if (left < right) {
            const mid = Math.floor((left + right) / 2)
            originalMergeSort(arr, left, mid)
            originalMergeSort(arr, mid + 1, right)
            originalMerge(arr, left, mid, right)
        }
    }

    function originalMerge(arr: Schedule[], left: number, mid: number, right: number) {
        const n1 = mid - left + 1
        const n2 = right - mid
        const L = arr.slice(left, left + n1)
        const R = arr.slice(mid + 1, mid + 1 + n2)

        let i = 0, j = 0, k = left
        while (i < n1 && j < n2) {
            const leftVal = getSortValue(L[i], sortKey.value)
            const rightVal = getSortValue(R[j], sortKey.value)
            arr[k++] = leftVal.localeCompare(rightVal) <= 0 ? L[i++] : R[j++]
        }
        while (i < n1) arr[k++] = L[i++]
        while (j < n2) arr[k++] = R[j++]
    }

    // ===== IMPROVED MERGE SORT (with run detection) =====
    function improvedMergeSort(arr: Schedule[], aux: Schedule[], left: number, right: number) {
        if (left < right) {
            const mid = Math.floor((left + right) / 2)
            improvedMergeSort(arr, aux, left, mid)
            improvedMergeSort(arr, aux, mid + 1, right)

            if (arr[mid].movie.title.localeCompare(arr[mid + 1].movie.title) <= 0) return
            mergeWithAux(arr, aux, left, mid, right)
        }
    }

    function mergeWithAux(arr: Schedule[], aux: Schedule[], left: number, mid: number, right: number) {
        for (let i = left; i <= right; i++) aux[i] = arr[i]
        let i = left, j = mid + 1, k = left
        while (i <= mid && j <= right) {
            const leftVal = getSortValue(aux[i], sortKey.value)
            const rightVal = getSortValue(aux[j], sortKey.value)
            arr[k++] = leftVal.localeCompare(rightVal) <= 0 ? aux[i++] : aux[j++]
        }
        while (i <= mid) arr[k++] = aux[i++]
    }

    // ===== RUN BOTH SORTS AND MEASURE TIME =====
    function runComparison() {
        const arr1 = JSON.parse(JSON.stringify(searchedData.value)) as Schedule[]
        const arr2 = JSON.parse(JSON.stringify(searchedData.value)) as Schedule[]

        const aux = new Array(arr2.length)

        const start1 = performance.now()
        originalMergeSort(arr1, 0, arr1.length - 1)
        const end1 = performance.now()

        const start2 = performance.now()
        improvedMergeSort(arr2, aux, 0, arr2.length - 1)
        const end2 = performance.now()

        originalSorted.value = arr1
        improvedSorted.value = arr2
        originalTime.value = end1 - start1
        improvedTime.value = end2 - start2
    }

    onMounted(() => {
        runComparison()
    })

    const paginatedData = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage
        return improvedSorted.value.slice(start, start + itemsPerPage)
    })


    watch(sortKey, () => {
        runComparison()
    })

    watch(searchQuery, () => {
        currentPage.value = 1
        runComparison()
    })

    watch(() => props.schedules, (newSchedules) => {
        data.value = [...newSchedules]
        runComparison()
    }, { immediate: true })

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
    <Head title="Schedules" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="flex justify-between">
                <div class="mb-4 w-[220px]">
                    <Label class="text-sm mb-2 ml-1">Sort By:</Label>
                    <Select id="sortKey" v-model="sortKey" class="w-[200px]">
                        <SelectTrigger>
                            <SelectValue placeholder="Sort By" />
                        </SelectTrigger>
                        <SelectContent>
                        <SelectGroup class="w-[200px]">
                            <SelectLabel for="sortKey">Sort By</SelectLabel>
                            <SelectItem value="title">Title</SelectItem>
                            <SelectItem value="city">City</SelectItem>
                            <SelectItem value="date">Date</SelectItem>
                            <SelectItem value="timeslot">Timeslot</SelectItem>
                        </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
                <div class="w-[500px]">
                    <Label class="text-sm mb-2 ml-1 italic">Search any keyword:</Label>
                    <Input type="text" placeholder="Search" v-model="searchQuery" />
                </div>
            </div>
            <Table>
                <TableCaption>
                    <p v-if="paginatedData.length > 0" >List of all movie schedules.</p>
                    <p v-else >No results.</p>
                </TableCaption>
                <TableHeader>
                <TableRow>
                    <TableHead>Movie</TableHead>
                    <TableHead>Genre</TableHead>
                    <TableHead>City</TableHead>
                    <TableHead>Cinema</TableHead>
                    <TableHead>Date</TableHead>
                    <TableHead>Timeslot</TableHead>
                    <TableHead class="flex justify-center">
                        <Label>Action</Label>
                    </TableHead>
                </TableRow>
                </TableHeader>
                <TableBody>
                <TableRow v-for="schedule in paginatedData" :key="schedule.id">
                    <TableCell>{{ schedule.movie.title }}</TableCell>
                    <TableCell>{{ schedule.movie.genre.name }}</TableCell>
                    <TableCell>{{ schedule.city.name }}</TableCell>
                    <TableCell>{{ schedule.cinema.name }}</TableCell>
                    <TableCell>{{ formatDate(schedule.show_date) }}</TableCell>
                    <TableCell>{{ schedule.timeslot.start_time + " - " + schedule.timeslot.end_time }}</TableCell>
                    <TableCell class="flex justify-center">
                        <Button @click="$inertia.visit(route('schedules.book', {id: schedule.id}))">
                            <TicketIcon /> Book
                        </Button>
                    </TableCell>
                </TableRow>
                </TableBody>
            </Table>

            <!-- Pagination -->
            <div class="mt-4 flex justify-center">
            <Pagination
                v-slot="{ page }"
                :items-per-page="itemsPerPage"
                :total="improvedSorted.length"
                :default-page="currentPage"
                @update:page="val => currentPage = val"
            >
                <PaginationContent v-slot="{ items }">
                <PaginationPrevious />
                <template v-for="(item, index) in items" :key="index">
                    <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === page"
                    >
                    {{ item.value }}
                    </PaginationItem>
                </template>
                <PaginationEllipsis :index="4" />
                <PaginationNext />
                </PaginationContent>
            </Pagination>
            </div>

            <!-- Comparison summary -->
            <div class="mt-6 text-center">
                <h2 class="text-lg font-semibold mb-2">🧠 Sorting Performance Comparison</h2>
                <p>Original Merge Sort: <strong>{{ originalTime.toFixed(3) }} ms</strong></p>
                <p>Improved Merge Sort (Run Detection): <strong>{{ improvedTime.toFixed(3) }} ms</strong></p>
                <p class="mt-2 text-sm text-gray-500">
                    Difference: <strong>{{ (originalTime - improvedTime).toFixed(3) }} ms</strong>
                </p>
            </div>
        </div>
    </AppLayout>
</template>