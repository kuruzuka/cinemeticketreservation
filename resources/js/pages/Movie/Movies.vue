<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';
    import type { BreadcrumbItem, Movie } from '@/types';
    import { ref, computed, onMounted, watch } from 'vue';
    import { Head, router, usePage } from '@inertiajs/vue3';
    import { toast } from 'vue-sonner';
    import { Button } from '@/components/ui/button';
    import { Label } from '@/components/ui/label';
    import { Input } from '@/components/ui/input';
    import {
        AlertDialog,
        AlertDialogAction,
        AlertDialogCancel,
        AlertDialogContent,
        AlertDialogDescription,
        AlertDialogFooter,
        AlertDialogHeader,
        AlertDialogTitle,
        AlertDialogTrigger,
    } from "@/components/ui/alert-dialog"
    import {
        Select,
        SelectContent,
        SelectGroup,
        SelectItem,
        SelectLabel,
        SelectTrigger,
        SelectValue,
    } from "@/components/ui/select";
    import {
        Table,
        TableBody,
        TableCaption,
        TableCell,
        TableHead,
        TableHeader,
        TableRow,
    } from '@/components/ui/table';
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
        { title: 'Movies', href: '/movies' },
    ];

    const props = defineProps<{
        movies: Movie[]
    }>()

    // ===== REACTIVE STATES =====
    const data = ref<Movie[]>([...props.movies])
    const originalSorted = ref<Movie[]>([])
    const improvedSorted = ref<Movie[]>([])
    const currentPage = ref(1)
    const itemsPerPage = 10

    const originalTime = ref(0)
    const improvedTime = ref(0)
    const searchQuery = ref('')

    // ===== SEARCH FUNCTIONALITY =====
    const searchedData = computed(() => {
        const query = searchQuery.value.toLowerCase().trim()
        if (!query) return data.value

        return data.value.filter(m => [
            m.title,
            m.author,
            m.director,
            m.genre.name
        ].some(field => field.toLowerCase().includes(query)))
    })

    // ===== SORTING LOGIC =====
    const sortKey = ref<'title' | 'author' | 'director'>('title')

    function getSortValue(movie: Movie, key: string): string {
        switch (key) {
            case 'title': return movie.title
            case 'author': return movie.author
            case 'director': return movie.director
            default: return ''
        }
    }

    // ===== ORIGINAL MERGE SORT =====
    function originalMergeSort(arr: Movie[], left: number, right: number) {
        if (left < right) {
            const mid = Math.floor((left + right) / 2)
            originalMergeSort(arr, left, mid)
            originalMergeSort(arr, mid + 1, right)
            originalMerge(arr, left, mid, right)
        }
    }

    function originalMerge(arr: Movie[], left: number, mid: number, right: number) {
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
    function improvedMergeSort(arr: Movie[], aux: Movie[], left: number, right: number) {
        if (left < right) {
            const mid = Math.floor((left + right) / 2)
            improvedMergeSort(arr, aux, left, mid)
            improvedMergeSort(arr, aux, mid + 1, right)

            if (arr[mid].title.localeCompare(arr[mid + 1].title) <= 0) return
            mergeWithAux(arr, aux, left, mid, right)
        }
    }

    function mergeWithAux(arr: Movie[], aux: Movie[], left: number, mid: number, right: number) {
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
        const arr1 = JSON.parse(JSON.stringify(searchedData.value)) as Movie[]
        const arr2 = JSON.parse(JSON.stringify(searchedData.value)) as Movie[]
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

    onMounted(() => runComparison())

    // ===== PAGINATION =====
    const paginatedData = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage
        return improvedSorted.value.slice(start, start + itemsPerPage)
    })


    // // ===== WATCHERS =====
    // watch(sortKey, () => {
    //     currentPage.value = 1
    //     runComparison()
    // })
    // watch(searchQuery, () => {
    //     currentPage.value = 1
    //     runComparison()
    // })
    watch([sortKey, searchQuery], () => {
        currentPage.value = 1
        runComparison()
    }, { flush: 'sync' })

    const page = usePage()

    const handleDelete = () => {
        router.delete(route('movie.destroy', { id: movieToDelete.value!.id }), {
            preserveState: false,
            onSuccess: () => {
                toast.success('Deletion Successful!', {
                    description: `Movie: ${movieToDelete.value!.title} has been deleted successfully.`,
                })
            },
            onError: () => {
                toast.error('Deletion Failed.', {
                    description: `Something went wrong while deleting the movie.`,
                })
            }
        })
    }

    const movieToDelete = ref<{ id: number; title: string } | null>(null)
    const showDialog = ref(false)

    // open dialog and remember which movie to delete
    const confirmDelete = (id: number, title: string) => {
        movieToDelete.value = { id, title }
        showDialog.value = true
    }

</script>

<template>
    <Head title="Movies" />
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
                                <SelectItem value="author">Author</SelectItem>
                                <SelectItem value="director">Director</SelectItem>
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
                    <p v-if="paginatedData.length > 0" >List of all movies.</p>
                    <p v-else >No results.</p>
                </TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>Title</TableHead>
                        <TableHead>Author</TableHead>
                        <TableHead>Director</TableHead>
                        <TableHead>Genre</TableHead>
                        <TableHead class="flex justify-center">
                            <Label>Action</Label>
                        </TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="movie in paginatedData" :key="movie.id">
                        <TableCell>{{ movie.title }}</TableCell>
                        <TableCell>{{ movie.author }}</TableCell>
                        <TableCell>{{ movie.director }}</TableCell>
                        <TableCell>{{ movie.genre.name }}</TableCell>
                        <TableCell class="flex justify-center space-x-2">
                            <Button class="bg-blue-600"
                            @click="$inertia.visit(route('movie.edit', {id: movie.id}))">
                                Edit
                            </Button>
                            <Button class="bg-red-600"
                            @click="confirmDelete(movie.id, movie.title)">
                                Delete
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
                    v-model:page="currentPage"
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

            <!-- Sorting performance summary -->
            <div class="mt-6 text-center">
                <h2 class="text-lg font-semibold mb-2">🧠 Sorting Performance Comparison</h2>
                <p>Original Merge Sort: <strong>{{ originalTime.toFixed(3) }} ms</strong></p>
                <p>Improved Merge Sort (Run Detection): <strong>{{ improvedTime.toFixed(3) }} ms</strong></p>
                <p class="mt-2 text-sm text-gray-500">
                    Difference: <strong>{{ (originalTime - improvedTime).toFixed(3) }} ms</strong>
                </p>
            </div>
            <AlertDialog v-model:open="showDialog">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Confirm Deletion</AlertDialogTitle>
                        <AlertDialogDescription>
                        Are you sure you want to delete this movie? This action cannot be undone.
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
