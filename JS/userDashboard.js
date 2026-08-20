const userNameEl = document.getElementById("userName");
const profileNameEl = document.getElementById("profileName");
const profileEmailEl = document.getElementById("profileEmail");
const profilePhoneEl = document.getElementById("profilePhone");

function renderProfile(user) {
    userNameEl.textContent = user.name ? user.name.split(" ")[0] : "";
    profileNameEl.textContent = user.name || "—";
    profileEmailEl.textContent = user.email || "—";
    profilePhoneEl.textContent = user.phone || "—";
}

async function loadProfile() {
    try {
        const res = await fetch("/api/user/profile");

        if (!res.ok) {
            throw new Error("Failed to load profile data");
        }

        const user = await res.json();
        renderProfile(user);
    } catch (error) {
        profileNameEl.textContent = "—";
        profileEmailEl.textContent = "—";
        profilePhoneEl.textContent = "—";
    }
}

loadProfile();