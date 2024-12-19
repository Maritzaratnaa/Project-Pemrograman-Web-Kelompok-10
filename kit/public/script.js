document.getElementById('registerForm').addEventListener('submit', function(event) {
    let valid = true;
    let errorMessages = [];

    document.querySelectorAll('.error').forEach(span => {
        span.textContent = '';
    });

    const username = document.getElementById('nama_user').value.trim();
    if (username === '') {
        valid = false;
        errorMessages.push('Username is required.');
    }

    const email = document.getElementById('email').value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        valid = false;
        errorMessages.push('Email is required.');
    } else if (!emailPattern.test(email)) {
        valid = false;
        errorMessages.push('Please enter a valid email address.');
    }

    const phone = document.getElementById('telepon').value.trim();
    if (phone === '') {
        valid = false;
        errorMessages.push('Phone number is required.');
    } else if (!/^\d{10,}$/.test(phone)) {
        valid = false;
        errorMessages.push('Phone number must be at least 10 digits and contain only numbers.');
    }

    const address = document.getElementById('alamat').value.trim();
    if (address === '') {
        valid = false;
        errorMessages.push('Address is required.');
    }

    const password = document.getElementById('password').value.trim();
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/;
    if (password === '') {
        valid = false;
        errorMessages.push('Password is required.');
    } else if (!passwordPattern.test(password)) {
        valid = false;
        errorMessages.push('Password must be at least 6 characters long, include at least one lowercase letter, one uppercase letter, and one number.');
    }

    if (!valid) {
        event.preventDefault(); 
        alert('Form submission failed due to the following errors:\n\n' + errorMessages.join('\n'));
    }
});


document.querySelectorAll('.btn-quantity').forEach(button => {
    button.addEventListener('click', () => {
        const action = button.getAttribute('data-action');
        const idDetailKeranjang = button.getAttribute('data-id');
        const quantityElement = button.parentElement.querySelector('.quantity');
        const itemElement = button.closest('.cart-item');
        const itemQuantityTextElement = itemElement.querySelector('.item-quantity-text');
        const itemSubtotalElement = itemElement.querySelector('.item-price');
        const totalPriceElement = document.querySelector('.total-price');
        const stock = parseInt(button.getAttribute('data-stock'), 10);

        const loadingSpinner = document.querySelector('.loading');
        loadingSpinner.style.display = 'inline-flex';

        let currentQuantity = parseInt(quantityElement.textContent, 10);

        if (action === 'increase') {
            if (currentQuantity >= stock) {
                alert('Jumlah barang sudah mencapai stok maksimum.');
                loadingSpinner.style.display = 'none';
                return;
            }
            currentQuantity++;
        } else if (action === 'decrease') {
            currentQuantity--;
        }

        if (currentQuantity < 1) {
            removeItem(idDetailKeranjang, itemElement, totalPriceElement);
            return;
        }

        quantityElement.textContent = currentQuantity;
        itemQuantityTextElement.textContent = `${currentQuantity} x`;

        updateQuantityOnServer(idDetailKeranjang, action, itemSubtotalElement, totalPriceElement, loadingSpinner);
    });
});


function updateQuantityOnServer(idDetailKeranjang, action, itemSubtotalElement, totalPriceElement, loadingSpinner) {
    fetch(`/keranjang/updateQuantity`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id_detail_keranjang: idDetailKeranjang, action: action }),
    })
    .then(response => response.json())
    .then(data => {
        loadingSpinner.style.display = 'none';

        if (data.success) {
            if (itemSubtotalElement) {
                itemSubtotalElement.textContent = `Rp ${data.newSubtotal}`;
            }

            const orderSummaryItems = document.querySelectorAll('.order-summary-items');
            orderSummaryItems.forEach(item => {
                const itemId = item.getAttribute('data-id');

                if (String(itemId) === String(idDetailKeranjang)) {
                    const priceElement = item.querySelector('.item-price');
                    if (priceElement) {
                        priceElement.textContent = `Rp ${data.newSubtotal}`;
                    }
                }
            });

            if (totalPriceElement) {
                totalPriceElement.textContent = `Total: Rp ${data.newTotal}`;
            }

            if (action === 'decrease' && data.newSubtotal === 'Rp 0') {
                removeItem(idDetailKeranjang, itemSubtotalElement.closest('.cart-item'), totalPriceElement);
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        loadingSpinner.style.display = 'none';
        console.error('Error during fetch:', error);
        alert('Terjadi kesalahan saat memperbarui kuantitas. Silakan coba lagi.');
    });
}

function removeItem(idDetailKeranjang, itemElement, totalPriceElement) {
    const loadingSpinner = document.querySelector('.loading');
    loadingSpinner.style.display = 'inline-flex'; 

    fetch(`/keranjang/removeItem`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id_detail_keranjang: idDetailKeranjang, action: 'decrease' }),
    })
    .then(response => response.json())
    .then(data => {
        loadingSpinner.style.display = 'none';
        if (data.success) {
            itemElement.remove();

            const orderSummaryItems = document.querySelectorAll('.order-summary-items');
            orderSummaryItems.forEach(item => {
                const itemId = item.getAttribute('data-id');
                if (String(itemId) === String(idDetailKeranjang)) {
                    item.remove();
                }
            });

            if (totalPriceElement) {
                totalPriceElement.textContent = `Total: Rp ${data.newTotal}`;
            }

            const cartItems = document.querySelectorAll('.cart-item');
            if (cartItems.length === 0) {
                const cartContainer = document.querySelector('.cart-items');
                cartContainer.innerHTML = `<p>Your shopping cart is empty. Start adding items from your favorite group now!</p>`;
            }

            const orderSummaryContainer = document.querySelector('.order-summary');
            const orderSummaryItemsLeft = document.querySelectorAll('.order-summary-items');
            if (orderSummaryItemsLeft.length === 0) {
                orderSummaryContainer.innerHTML = `<h4>Order Summary</h4>
                                                   <p>No order summary available yet.</p>`;
            }
        } else {
            alert('Gagal menghapus item: ' + data.message);
        }
    })
    .catch(error => {
        loadingSpinner.style.display = 'none';
        console.error('Error removing item:', error);
    });
}

function updateCartCount(id_user) {
    fetch(`/keranjang/cartCount/${id_user}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const cartCountElement = document.querySelector('.cart-count');
            cartCountElement.textContent = data.cartCount; 
        } else {
            console.error('Gagal memperbarui jumlah keranjang:', data.message);
        }
    })
    .catch(error => {
        console.error('Error fetching cart count:', error);
    });
}


document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('input[name="query"]');
    const resultContainer = document.createElement('div');
    resultContainer.className = 'autocomplete-results';
    searchInput.parentNode.appendChild(resultContainer);

    searchInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length < 2) {
            resultContainer.innerHTML = '';
            return;
        }

        fetch(`/search/ajax?query=${query}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(response => response.json())
            .then(data => {
                resultContainer.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const resultItem = document.createElement('div');
                        resultItem.className = 'result-item';
                        
                        resultItem.innerHTML = `
                                    <strong>${item.nama_barang}</strong><br>

                        `;

                        resultItem.addEventListener('click', () => {
                            searchInput.value = item.nama_barang;
                            const form = searchInput.closest('form');
                            form.submit();
                        });

                        resultContainer.appendChild(resultItem);
                    });
                } else {
                    resultContainer.innerHTML = '<div class="no-result">No products found</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    });
});