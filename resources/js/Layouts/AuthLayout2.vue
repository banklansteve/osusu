<template>
    <header>
      <!-- Navbar -->
      <nav
        :class="[
          'fixed top-0 w-full z-50 flex items-center px-6 py-4 transition-all duration-300',
          isTransparent ? 'bg-transparent text-primary' : 'bg-primary text-white shadow-md'
        ]"
      >
        <!-- Logo Section -->
        <div class="flex items-center justify-between w-full lg:w-auto">
          <!-- App Logo/Name -->
          <Link href="/" class="text-2xl font-bold">
            Osusu
          </Link>
  
          <!-- Hamburger Icon -->
          <button
            @click="toggleMenu"
            class="lg:hidden text-2xl focus:outline-none transition-transform duration-300"
          >
            <span v-if="!menuOpen">☰</span>
            <span v-else>×</span>
          </button>
        </div>
  
        <!-- Navigation Links for Larger Screens -->
        <ul
          v-if="!menuOpen"
          class="hidden lg:flex space-x-8 ml-auto items-center"
        >
          <template v-if="authUser">
             <li class="text-white">{{ authUser.fullname }}</li>
              <li v-for="link in navLinks" :key="link.name">
                <Link
                  :href="link.href"
                  :class="[
                'relative font-medium transition-all duration-300',
                isActiveLink(link.href) ? 'text-secondary_var3' : 'hover:text-secondary_var3'
              ]">
                  {{ link.name }}
                  <!-- Underline Effect -->
                <span
                  class="absolute left-0 bottom-0 w-full h-0.5 bg-secondary_var3 scale-x-0 transition-transform duration-300 origin-center hover:scale-x-100"
                ></span>
                </Link>
              </li>
              <li class="cursor-pointer ">
                <button id="notification-button" class="relative" @click.stop="toggleNotifications">
                    <AnOutlinedBell class="text-xl" />
                    <span v-if="notifications.length > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full">{{ notifications.length > 0 ? notifications.length : '' }}</span>
                </button>
                <div class="animate__animated animate__slideInDown">
                    <div v-show="openNotification" id="notification-menu" class="absolute right-0 mt-2 w-96 bg-white shadow-lg rounded-lg overflow-hidden z-50 border border-gray-200">
                      <ul class="divide-y divide-gray-200"  v-if="notifications?.length > 0">
                          <li v-for="notification in notifications" :key="notification.id" class="p-4 text-primary hover:bg-gray-100 cursor-pointer transition-all ease-in-out duration-300">
                              <Link @click="markAsRead(notification.id)" v-if="notification.data?.url" :href="notification.data.url" class="text-primary font-semibold text-sm">{{ notification.data.message }}</Link>
                          </li>
                      </ul>
                      <ul v-else>
                        <li class="text-sm text-gray-600 p-4">There are no notifications</li>
                      </ul>
                      
                      <!-- <ul class="divide-y divide-gray-200">
                          <li @click="isOpen = false" class="p-3 text-primary hover:bg-gray-100 cursor-pointer">
                            📩 You have a new message
                          </li>
                          <li @click="isOpen = false" class="p-3 text-primary hover:bg-gray-100 cursor-pointer">
                            💰 Payment received from John Doe
                          </li>
                          <li @click="isOpen = false" class="p-3 text-primary hover:bg-gray-100 cursor-pointer">
                            ✅ Your request to join a savings group was approved
                          </li>
                        </ul> -->
                    </div>
                </div>
              </li>
              <li>
                <Link :href="route('logout')" method="post" as="button" class="text-white border border-transparent px-4 py-2 text-sm rounded-full hover:text-gray-100 hover:border-gray-100 focus:ring-offset-2 transition-all ease-in-out duration-300">Logout</Link>
              </li>
            </template>
            <template v-else>
                <li v-for="link in links" :key="link.name">
                  <Link
                    :href="link.href"
                    :class="[
                  'relative font-medium transition-all duration-300',
                  isActiveLink(link.href) ? 'text-secondary_var3' : 'hover:text-secondary_var3'
                ]">
                    {{ link.name }}
                    <!-- Underline Effect -->
                  <span
                    class="absolute left-0 bottom-0 w-full h-0.5 bg-secondary_var3 scale-x-0 transition-transform duration-300 origin-center hover:scale-x-100"
                  ></span>
                  </Link>
                </li>
            </template>
        </ul>
      </nav>
  
      <!-- Full-Screen Menu for Smaller Screens -->
      <div v-if="menuOpen" class="fixed inset-0 bg-primary text-white flex flex-col items-center justify-center space-y-8 z-40">
        <button @click="toggleMenu" class="absolute top-6 right-6 text-3xl focus:outline-none">
          ×
        </button>
  
        <ul class="flex flex-col space-y-6" v-if="authUser">
          <li v-for="link in navLinks" :key="link.name">
            <Link  @click="toggleMenu" :href="link.href" class="relative hover:text-red-700 font-medium text-2xl hover:underline transition-transform duration-300">
              {{ link.name }}
            </Link>
          </li>
        </ul>

        <ul class="flex flex-col space-y-6" v-else>
          <li v-for="link in links" :key="link.name">
            <Link @click="toggleMenu" :href="link.href" class="relative hover:text-red-700 font-medium text-2xl hover:underline transition-transform duration-300">
              {{ link.name }}
            </Link>
          </li>
        </ul>
      </div>
    </header>
  
    <!-- Content Slot -->
    <main class="pt-20">
      <slot />
    </main>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, onUnmounted } from "vue";
  import { usePage, Link } from '@inertiajs/vue3';
  import { AnOutlinedBell } from '@kalimahapps/vue-icons';

  const props = defineProps({
    saving: Number
  })

    // states
    const menuOpen = ref(false);
    const page = usePage();

    // const savingId = inject('savingId')

    // const { props } = usePage();
    // const authUser = page.auth?.user || null;
    const authUser = usePage().props.auth.user;

    const openNotification = ref(false)

    const notifications = ref([]);
 
    // Determine if the navbar should be transparent based on the current route
    const isTransparent = computed(() => page.url === "/");

    const isActiveLink = (href) => {
      return page.url === href;
    };


    // Navigation links for authenticated users
    const navLinks = [
      { name: "Dashboard", href: "/dashboard" },
      { name: "Create New", href: "/create-new" },
      { name: "Savings", href: "/my-savings" },
      { name: "Profile", href: "/profile" },
      { name: "Notifications", href: "/notifications" },
      // { name: "Logout", href: "/logout" },
    ];

    const links = [ //non-auth users
      { name: "About Us", href: "/about-us" },
      { name: "OUr Service", href: "/service" },
      { name: "Login", href: "/login" },
      { name: "Register", href: "/register" },
    ];


    const toggleMenu = () => {
        menuOpen.value = !menuOpen.value;
    };

    const toggleNotifications = () => {
      openNotification.value = !openNotification.value
    }

      // Close menu when clicking outside
    const closeNotification = (event) => {
        if (!event.target.closest('#notification-menu') && !event.target.closest('#notification-button')) {
            openNotification.value = false;
        }
    };

    // axios.defaults.withCredentials = true;
    const fetchNotifications = async () => {
      await axios.get('/sanctum/csrf-cookie');
        try {
            const response = await fetch('/api/notifications', {
                credentials: 'include',  // Required for Sanctum authentication
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            notifications.value = await response.json();
            console.log(response);
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    };

    const markAsRead = async(notificationId) => {
      try {
        await fetch('/sanctum/csrf-cookie', {
            credentials: 'include',
        });

          const response = await fetch(`/api/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
            },
            credentials: 'include',
          });

          if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

          fetchNotifications(); // Refresh notifications after marking as read
      } catch (error) {
          console.error('Error marking notification as read:', error);
      }
    };

    const getCookie = (name) => {
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            let [key, value] = cookie.split('=');
            if (key.trim() === name) return decodeURIComponent(value);
        }
        return null;
    };

    // Listen for clicks outside the menu
    onMounted(() => {
        document.addEventListener('click', closeNotification);
        fetchNotifications()
        
        // window.Echo.private(`savings.${props.saving?.savings_uid}.creator`)
        // .listen('MembershipInviteAccepted', (e) => {
        //   console.log('User received a notification: ', e)
        //   notifications.value.unshift(e)
        // })

        const authUser = usePage().props.auth.user;
        console.log(notifications.value)
        window.Echo.private(`App.Models.User.${authUser.id}`)
          .notification((notification) => {
            // notifications.value.unshift(notification)
            const notif = {
              data: {
                  message: notification.message,
                  url: notification.url,
                  saving_id: notification.saving_id,
                  invite_token: notification.invite_token,
                  invited_by: notification.invited_by
              }
            }
            notifications.value.push(notif)
            console.log("here are the notifications for the user ", notification);
        });
        
    });

    onUnmounted(() => {
        document.removeEventListener('click', closeNotification);
    });
  </script>
  
  <style scoped>
  /* Add your app-specific colors */
  
  
  </style>
  