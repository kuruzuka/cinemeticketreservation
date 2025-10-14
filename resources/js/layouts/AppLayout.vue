<script setup lang="ts">
import type { BreadcrumbItemType } from '@/types';
import { Toaster } from '@/components/ui/sonner';
import 'vue-sonner/style.css'
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppSidebarLayout :breadcrumbs="breadcrumbs">
        <slot />
        <Toaster position="top-right"/>
    </AppSidebarLayout>
</template>
