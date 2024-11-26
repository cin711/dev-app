<template>
    <ul class="tree">
        <li v-for="node in nodes" :key="node.id">
            <span>{{ node.name }}</span>
            <ul v-if="node.children.length > 0" class="tree">
                <Tree :nodes="node.children" />
            </ul>
        </li>
    </ul>
</template>
<script>
export default {
    name: 'Tree',
    props: {
        nodes: Array
    }
}
</script>
<style>
.tree {
    list-style-type: none;
    padding-left: 0;
}

.tree li {
    position: relative;
    padding-left: 20px;
}

.tree li::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    border: 1px solid black;
    width: 10px;
    height: 10px;
}

.tree li::after {
    content: "";
    position: absolute;
    left: 10px;
    top: 10px;
    border-left: 1px solid black;
    height: 100%;
}

.tree li > ul {
    margin-top: 10px;
}
</style>