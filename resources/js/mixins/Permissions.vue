<script>
  export default {
    methods: {
      $can(permissionName) {
          console.log(Permissions.indexOf(permissionName) !== -1, "boo")
        return Permissions.indexOf(permissionName) !== -1;
      },
    },
  };
</script>
