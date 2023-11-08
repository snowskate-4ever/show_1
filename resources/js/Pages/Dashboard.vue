<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Исполнители" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Исполнители</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="d-inline-flex px-1" style = "width:150px; height:100%;">
                        <ul class="list-unstyled">
                            <li class="mb-1">
                                <button class="btn d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#table-collapse" aria-expanded="false">
                                    Таблицы
                                </button>
                                <div class="px-3 collapse" id="table-collapse">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                        <li><a href="{{url('/admn/countries')}}" class="link-dark d-inline-flex text-decoration-none rounded">Страны</a></li>
                                        <li><a href="{{url('/admn/regions')}}" class="link-dark d-inline-flex text-decoration-none rounded">Регионы</a></li>
                                        <li><a href="{{url('/admn/cities')}}" class="link-dark d-inline-flex text-decoration-none rounded">Города</a></li>
                                        <li><a href="{{url('/admn/types')}}" class="link-dark d-inline-flex text-decoration-none rounded">Типы</a></li>
                                        <li><a href="{{url('/admn/type_groups')}}" class="link-dark d-inline-flex text-decoration-none rounded">Группы типов</a></li>
                                        <li><a href="{{url('/admn/urls')}}" class="link-dark d-inline-flex text-decoration-none rounded">Ссылки</a></li>
                                        <li><a href="{{url('/admn/instruments')}}" class="link-dark d-inline-flex text-decoration-none rounded">Инструменты</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="p-6 text-gray-900">Ура, ты внутри!)</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
