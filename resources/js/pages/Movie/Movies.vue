<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue'
    import type { BreadcrumbItem } from '@/types';

    const breadcrumbs : BreadcrumbItem[] = [
        {
            title: 'Movies',
            href: '/movies'
        },
    ];

</script>

<template>
    
    <AppLayout :breadcrumbs="breadcrumbs">
        
        <div class="p-4">
            <h1>Movies Page</h1>
        </div>

    </AppLayout>

</template>