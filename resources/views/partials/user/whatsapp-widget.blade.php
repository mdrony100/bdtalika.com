<style>
    .user-whatsapp-widget {
        position: fixed;
        top: 50%;
        right: 18px;
        transform: translateY(-50%);
        z-index: 9998;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .user-whatsapp-widget:hover {
        text-decoration: none;
    }

    .user-whatsapp-widget-label {
        background: #ffffff;
        color: #17202a;
        border: 1px solid rgba(37, 211, 102, 0.24);
        border-radius: 8px;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.2;
        padding: 8px 10px;
        white-space: nowrap;
        opacity: 0;
        transform: translateX(8px);
        transition: all 0.2s ease;
    }

    .user-whatsapp-widget:hover .user-whatsapp-widget-label {
        opacity: 1;
        transform: translateX(0);
    }

    .user-whatsapp-widget-button {
        position: relative;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: #25d366;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 30px rgba(37, 211, 102, 0.38);
        animation: whatsapp-blink 1.35s infinite;
    }

    .user-whatsapp-widget-button::before,
    .user-whatsapp-widget-button::after {
        content: "";
        position: absolute;
        inset: -6px;
        border-radius: 50%;
        border: 2px solid rgba(37, 211, 102, 0.45);
        animation: whatsapp-ring 1.8s infinite;
    }

    .user-whatsapp-widget-button::after {
        animation-delay: 0.55s;
    }

    .user-whatsapp-widget-button img {
        width: 33px;
        height: 33px;
        object-fit: contain;
        position: relative;
        z-index: 1;
    }

    @keyframes whatsapp-blink {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.08);
        }
    }

    @keyframes whatsapp-ring {
        0% {
            opacity: 0.8;
            transform: scale(0.88);
        }
        100% {
            opacity: 0;
            transform: scale(1.45);
        }
    }

    @media (max-width: 575px) {
        .user-whatsapp-widget {
            right: 12px;
        }

        .user-whatsapp-widget-label {
            display: none;
        }

        .user-whatsapp-widget-button {
            width: 52px;
            height: 52px;
        }
    }
</style>

<a class="user-whatsapp-widget"
    href="https://wa.me/+8801738411242"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Chat with us on WhatsApp">
    <span class="user-whatsapp-widget-label">Chat with Us</span>
    <span class="user-whatsapp-widget-button">
        <img src="{{ asset('assets/images/whatsapp.png') }}" alt="WhatsApp">
    </span>
</a>
