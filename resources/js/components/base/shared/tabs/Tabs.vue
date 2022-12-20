<template>
    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
        <li class="nav-item" v-for="tab in tabs">
            <a
                :class="`nav-link ${tab.props.active ? 'active' : ''}`"
                data-bs-toggle="tab"
                :href="`#${tab.props.tab}`"
                role="tab"
                @click="$emit('changeTab', { tab: tab.props.tab })"
            >
                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                <span class="d-none d-sm-block">{{ tab.props.title }}</span>
            </a>
        </li>
    </ul>
    <div class="tab-content" id="nav-tabContent">
        <slot></slot>
    </div>
</template>

<script>
import {ref} from "vue";

export default {
    name: "Tabs",
    emits: ['changeTab'],
    setup(props, {slots}) {
        const tabs = ref(_.filter(slots.default(), slot => {
            return slot.props;
        }));

        return {
            tabs,
        };
    },
};
</script>

<style scoped></style>
