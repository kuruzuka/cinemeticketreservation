<script setup lang="ts">
    import { ref, watch, computed } from 'vue'
    import AppLayout from '@/layouts/AppLayout.vue'
    import { Movie, type BreadcrumbItem } from '@/types'
    import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
    } from '@/components/ui/table'
    import { Button } from '@/components/ui/button'
    import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select'
    import { Label } from '@/components/ui/label'
    import { 
        Pagination, PaginationContent, 
        PaginationItem, PaginationEllipsis,
        PaginationFirst, PaginationLast,
        PaginationNext, PaginationPrevious
    } from '@/components/ui/pagination'
    import { SquareMousePointerIcon, SquarePenIcon, Trash2Icon } from 'lucide-vue-next'
    import { Link } from '@inertiajs/vue3'
    import { route } from 'ziggy-js'
    import { router } from '@inertiajs/vue3'

    const currentPage = ref(1) as any
    const perPage = ref(10)

    const totalPages = computed(() => Math.ceil(localMovies.value.length / perPage.value))

    const paginatedMovies = computed(() => {
        const start = (currentPage.value - 1) * perPage.value
        return localMovies.value.slice(start, start + perPage.value)
    })

    const visiblePages = computed(() => {
        const total = totalPages.value
        const current = currentPage.value
        const delta = 2 // how many pages to show around current
        const range: number[] = []
        const rangeWithDots: (number | string)[] = []

        let start = Math.max(2, current - delta)
        let end = Math.min(total - 1, current + delta)

        // adjust window when near start or end
        if (current - delta <= 2) end = 1 + 2 * delta
        if (current + delta >= total - 1) start = total - 2 * delta

        // clamp
        start = Math.max(2, start)
        end = Math.min(total - 1, end)

        // fill range
        for (let i = start; i <= end; i++) range.push(i)

        // build with ellipses
        if (start > 2) rangeWithDots.push('...')
        rangeWithDots.push(...range)
        if (end < total - 1) rangeWithDots.push('...')

        return [1, ...rangeWithDots, total].filter(
            (v, i, arr) => total > 1 && (i === 0 || i === arr.length - 1 || v !== arr[i - 1])
        )
    })



    // --- Breadcrumbs ---
    const breadcrumbs: BreadcrumbItem[] = [
        { 
            title: 'Movies', 
            href: '/movies' 
        },
    ]

    // --- Props ---
    interface Props {
        movies: Movie[],
    }
    const props = defineProps<Props>()

    // --- Traditional Merge Sort ---
    function mergeSort<T>(arr: T[], compareFn: (a: T, b: T) => number): T[] {
        if (arr.length <= 1) return arr

        const mid = Math.floor(arr.length / 2)
        const left = mergeSort(arr.slice(0, mid), compareFn)
        const right = mergeSort(arr.slice(mid), compareFn)

        return merge(left, right, compareFn)
    }

    function merge<T>(left: T[], right: T[], compareFn: (a: T, b: T) => number): T[] {
        const result: T[] = []
        let i = 0, j = 0
        while (i < left.length && j < right.length) {
            if (compareFn(left[i], right[j]) <= 0) result.push(left[i++])
            else result.push(right[j++])
        }
        return result.concat(left.slice(i)).concat(right.slice(j))
    }

    // --- Improved Merge Sort ---
    function improvedMergeSort<T>(arr: T[], aux: T[], left: number, right: number, 
    compareFn: (a: T, b: T) => number) : void {
        if (left < right) {
            const mid = Math.floor((left + right) / 2)
            improvedMergeSort(arr, aux, left, mid, compareFn)
            improvedMergeSort(arr, aux, mid + 1, right, compareFn)
            if (compareFn(arr[mid], arr[mid + 1]) <= 0) return // run detection
            mergeWithAux(arr, aux, left, mid, right, compareFn)
        }
    }

    function mergeWithAux<T>(arr: T[], aux: T[], left: number, mid: number, right: number,
    compareFn: (a: T, b: T) => number
    ) : void {
        for (let i = left; i <= right; i++) aux[i] = arr[i]
        let i = left, j = mid + 1, k = left
        while (i <= mid && j <= right) {
            if (compareFn(aux[i], aux[j]) <= 0) arr[k++] = aux[i++]
            else arr[k++] = aux[j++]
        }
        while (i <= mid) arr[k++] = aux[i++]
    }


    // COMPARISON LOGIC
    const localMovies = ref([...props.movies])
    const sortKey = ref<'title' | 'author' | 'director' | 'cinema' | 'timeslot'>('title')
    const results = ref<{ name: string; time: number; mem: number }[]>([])

    const handleAddMovie = () => alert('clicked')

    const measurePerformance = (fn: () => void): { time: number; mem: number } => {
        const memBefore = (performance as any).memory?.usedJSHeapSize ?? 0
        const t0 = performance.now()
        fn()
        const t1 = performance.now()
        const memAfter = (performance as any).memory?.usedJSHeapSize ?? 0
        const memDiff = Math.abs(memAfter - memBefore)
        return {
            time: t1 - t0,
            mem: memDiff,
        }
    }


    const runSorts = () => {
        const compareFn = (a: Movie, b: Movie) => {
            if (sortKey.value === 'timeslot') {
            const toMinutes = (time: string) => {
                const [h, m] = time.split(':').map(Number)
                return h * 60 + m
            }
            return toMinutes(a.start) - toMinutes(b.start)
            }
            return a[sortKey.value].localeCompare(b[sortKey.value])
        }

        const base = [...props.movies]
        const aux = new Array(base.length)

        const resultSet: { name: string; time: number; mem: number }[] = []

        // Traditional
        let sorted1: Movie[] = []
        const r1 = measurePerformance(() => {
            sorted1 = mergeSort([...base], compareFn)
        })
        resultSet.push({ name: 'Traditional Merge Sort', ...r1 })

        // Improved
        let sorted2 = [...base]
        const r2 = measurePerformance(() => {
            improvedMergeSort(sorted2, aux, 0, sorted2.length - 1, compareFn)
        })
        resultSet.push({ name: 'Improved Merge Sort', ...r2 })

        // Display results
        results.value = resultSet

        // Show the improved-sorted list
        localMovies.value = sorted2
        }

        // Automatically run sorts whenever user changes key
        watch(sortKey, () => runSorts(), { immediate: true })
        watch(sortKey, () => {
        currentPage.value = 1
        runSorts()
    }, { immediate: true })

    const handleDelete = (id: number) => {
        if (confirm("Are you sure you want to delete?")) {
            router.delete(route('movie.destroy', {id}))
        }
    }

</script>


<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-6">
        <div class="flex gap-4 items-center">
            <Button class="w-[160px]" @click="handleAddMovie">Add Movie</Button>
            <Label class="ml-5">Sort By: </Label>
            <Select v-model="sortKey">
            <SelectTrigger class="w-[200px]">
                <SelectValue placeholder="Sort by..." />
            </SelectTrigger>
            <SelectContent>
                <SelectItem value="title">Title</SelectItem>
                <SelectItem value="director">Director</SelectItem>
                <SelectItem value="cinema">Cinema</SelectItem>
                <SelectItem value="timeslot">Timeslot (Start)</SelectItem>
            </SelectContent>
            </Select>
        </div>

        <Table>
            <TableHeader>
            <TableRow>
                <TableHead>ID</TableHead>
                <TableHead>Title</TableHead>
                <TableHead>Author</TableHead>
                <TableHead>Director</TableHead>
                <TableHead>Cinema</TableHead>
                <TableHead>Timeslot</TableHead>
                <TableHead class="text-center">Action</TableHead>
            </TableRow>
            </TableHeader>

            <TableBody>
            <TableRow v-for="movie in paginatedMovies" :key="movie.id">
                <TableCell>{{ movie.id }}</TableCell>
                <TableCell>{{ movie.title }}</TableCell>
                <TableCell>{{ movie.author }}</TableCell>
                <TableCell>{{ movie.director }}</TableCell>
                <TableCell>{{ movie.cinema }}</TableCell>
                <TableCell>{{ movie.start + ' - ' + movie.end }}</TableCell>
                <TableCell class="space-x-2 flex justify-center">
                    <Link :href="route('movie.book', {id: movie.id})">
                        <Button>
                            <SquareMousePointerIcon /> Book
                        </Button>
                    </Link>
                    <Link :href="route()">
                        <Button class="bg-blue-500">
                            <SquarePenIcon /> Edit
                        </Button>
                    </Link>
                    <Link>
                        <Button class="bg-red-500" @click="handleDelete(movie.id)">
                            <Trash2Icon /> Delete
                        </Button>
                    </Link>
                </TableCell>
            </TableRow>
            </TableBody>
        </Table>
        <div class="flex justify-start mt-6 ">
            <Pagination :items-per-page="perPage">
                <PaginationContent>
                
                    <PaginationFirst
                    :disabled="currentPage === 1"
                    @click="currentPage = 1"
                    />

                    <PaginationPrevious
                    :disabled="currentPage === 1"
                    @click="currentPage--"
                    />

                    <PaginationItem
                        v-for="(page, idx) in visiblePages"
                        :key="idx"
                        >
                        <template v-if="page === '...'">
                            <PaginationEllipsis />
                        </template>
                        <template v-else>
                            <Button
                            variant="outline"
                            size="sm"
                            :class="page === currentPage ? 'bg-primary text-white' : ''"
                            @click="currentPage = page"
                            >
                            {{ page }}
                            </Button>
                        </template>
                    </PaginationItem>
                    
                    <PaginationNext
                    class="ml-2"
                    :disabled="currentPage === totalPages"
                    @click="currentPage++"
                    />
                
                    <PaginationLast
                    :disabled="currentPage === totalPages"
                    @click="currentPage = totalPages"
                    />
                    
                </PaginationContent>
            </Pagination>
        </div>


      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Algorithm</TableHead>
            <TableHead>Execution Time (ms)</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="r in results" :key="r.name">
            <TableCell>{{ r.name }}</TableCell>
            <TableCell>{{ r.time.toFixed(3) }}</TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>
  </AppLayout>
</template>
