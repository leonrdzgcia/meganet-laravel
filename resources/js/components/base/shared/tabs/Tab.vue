<template>
    <div
        :class="`tab-pane fade ${active ? 'show active' : ''}`"
        :id="tab"
        role="tabpanel"
        :aria-labelledby="`nav-${title}`"
    >
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "Tab",
    props: {
        tab: String,
        title: String,
        active: {
            type: Boolean,
            default: false
        }
    }
};
</script>

<style scoped></style>
