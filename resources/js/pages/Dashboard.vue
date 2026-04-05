<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { dashboard, toggleShift } from '@/routes';
import type { User } from '@/types';

defineProps<{ user: User }>();
defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const { url, method } = toggleShift();
async function toggle() {
    const resp = await fetch(url, {
        method: method,
    });
    router.reload({ only: ['user'] });
}
</script>

<template>
    <Head title="Dashboard" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
        </div>
        <div
            class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-2 md:min-h-min dark:border-sidebar-border"
        >
            <h3 class="flex flex-row justify-between">
                <span class="text-xl">Shifts</span>
                <button @click="toggle">
                    {{ user.open_shift ? 'End Shift' : 'New Shift' }}
                </button>
            </h3>
            <table class="table-auto [&_tr>*]:p-1">
                <thead>
                    <tr>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="shift in user.shifts" :key="shift.id">
                        <td>
                            <time
                                :datetime="shift.start_time"
                                v-text="
                                    new Date(shift.start_time).toLocaleString()
                                "
                                />
                        </td>
                        <td>
                            <time
                                v-if="shift.end_time"
                                :datetime="shift.end_time"
                                v-text="
                                    new Date(shift.end_time).toLocaleString()
                                " />
                            <template v-else>
                                <span>In progress</span>
                            </template>
                        </td>
                        <td>{{ shift.status }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
