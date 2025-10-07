<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue'
    import type { BreadcrumbItem } from '@/types';

    const breadcrumbs : BreadcrumbItem[] = [
        {
            title: 'Bookings',
            href: '/bookings'
        },
    ];

</script>

<template>
    
    <AppLayout :breadcrumbs="breadcrumbs">
        
        <div class="p-4">
            <h1>Booking Page</h1>
        </div>

    </AppLayout>

</template>