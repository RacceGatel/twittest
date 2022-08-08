<template>
    <div class="block w-full sm:max-w-md mt-2 px-2 py-2 bg-white shadow-md sm:rounded-lg">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="text-sm pt-2 font-bold pl-2 bg-white  border-gray-200">
                {{ twit.username }}
            </div>
            <div class="text-sm p-2 pt-1 break-all bg-white border-t border-gray-200">
                {{ twit.content }}
            </div>
            <div class="text-xs text-right pt-1 pr-2 bg-white border-t border-gray-200">
                {{ new Date(twit.created_at).toLocaleString("ru-RU") }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "message",
    props: {
        twit: Object,
    },
}
</script>
