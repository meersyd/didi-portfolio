import Alpine from 'alpinejs';

const POSES = ['idle', 'coding', 'walking', 'thinking', 'celebrating', 'calling', 'reading'];

Alpine.store('mirza', {
    pose: 'idle',
    manualUntil: 0,
    setPose(pose) {
        if (Date.now() < this.manualUntil) {
            return;
        }

        this.pose = pose;
    },
    dragging: false,
    poke() {
        if (this.dragging) {
            return;
        }

        const current = POSES.indexOf(this.pose);
        this.pose = POSES[(current + 1) % POSES.length];
        this.manualUntil = Date.now() + 2800;
    },
});

Alpine.store('theme', {
    dark: false,
    init() {
        const stored = window.localStorage.getItem('mirza-theme');

        if (stored === 'dark' || stored === 'light') {
            this.dark = stored === 'dark';
        } else {
            this.dark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        }

        this.apply();
    },
    toggle() {
        this.dark = ! this.dark;
        window.localStorage.setItem('mirza-theme', this.dark ? 'dark' : 'light');
        this.apply();
    },
    apply() {
        document.documentElement.classList.toggle('dark', this.dark);
        document.documentElement.style.colorScheme = this.dark ? 'dark' : 'light';

        const icon = document.querySelector('link[rel="icon"]');

        if (icon?.dataset.iconLight && icon?.dataset.iconDark) {
            icon.href = this.dark ? icon.dataset.iconDark : icon.dataset.iconLight;
        }
    },
});

Alpine.store('palette', {
    open: false,
    toggle() {
        this.open = ! this.open;
    },
    close() {
        this.open = false;
    },
});

Alpine.data('navbar', () => ({
    scrolled: false,
    open: false,
    init() {
        this.onScroll();
        window.addEventListener('scroll', () => this.onScroll(), { passive: true });
    },
    onScroll() {
        this.scrolled = window.scrollY > 12;
    },
    close() {
        this.open = false;
    },
}));

Alpine.data('characterActor', (initial = 'idle', reactive = false, clickable = false, cycle = false) => ({
    pose: initial,
    poses: POSES,
    timer: null,
    init() {
        if (reactive) {
            this.pose = Alpine.store('mirza').pose || initial;
            this.$watch(
                () => Alpine.store('mirza').pose,
                (value) => {
                    if (value) {
                        this.pose = value;
                    }
                },
            );
        }

        if (cycle && ! window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.startCycle();
        }
    },
    startCycle() {
        this.stopCycle();
        this.timer = window.setInterval(() => this.next(false), 3200);
    },
    stopCycle() {
        if (this.timer) {
            window.clearInterval(this.timer);
            this.timer = null;
        }
    },
    poke() {
        Alpine.store('mirza').poke();

        if (! reactive) {
            this.pose = Alpine.store('mirza').pose;
        }
    },
    next(restart = true) {
        if (clickable || reactive) {
            this.poke();
            return;
        }

        const current = this.poses.indexOf(this.pose);
        this.pose = this.poses[(current + 1) % this.poses.length];

        if (restart && this.timer) {
            this.startCycle();
        }
    },
    destroy() {
        this.stopCycle();
    },
}));

Alpine.data('characterDock', (home = false) => ({
    on: ! home,
    minimized: false,
    x: null,
    y: null,
    dragging: false,
    moved: false,
    startX: 0,
    startY: 0,
    originX: 0,
    originY: 0,
    moveListener: null,
    upListener: null,
    init() {
        this.restore();

        if (home) {
            const check = () => {
                this.on = window.scrollY > 260;
            };

            check();
            window.addEventListener('scroll', check, { passive: true });
        }

        window.addEventListener('resize', () => this.clamp());
        this.$nextTick(() => this.clamp());
    },
    get placed() {
        return this.x !== null && this.y !== null;
    },
    get dockStyle() {
        if (! this.placed) {
            return {};
        }

        return {
            left: `${this.x}px`,
            top: `${this.y}px`,
            right: 'auto',
            bottom: 'auto',
        };
    },
    restore() {
        try {
            const saved = JSON.parse(window.localStorage.getItem('mirza-dock') || '{}');

            this.minimized = Boolean(saved.minimized);

            if (typeof saved.x === 'number' && typeof saved.y === 'number') {
                this.x = saved.x;
                this.y = saved.y;
            }
        } catch (error) {
            this.minimized = false;
        }
    },
    persist() {
        window.localStorage.setItem('mirza-dock', JSON.stringify({
            minimized: this.minimized,
            x: this.x,
            y: this.y,
        }));
    },
    clamp() {
        if (! this.placed) {
            return;
        }

        const width = this.$el.offsetWidth || (this.minimized ? 132 : 184);
        const height = this.$el.offsetHeight || (this.minimized ? 40 : 220);
        const maxX = Math.max(8, window.innerWidth - width - 8);
        const maxY = Math.max(8, window.innerHeight - height - 8);

        this.x = Math.min(Math.max(8, this.x), maxX);
        this.y = Math.min(Math.max(8, this.y), maxY);
    },
    startDrag(event) {
        if (event.pointerType === 'mouse' && event.button !== 0) {
            return;
        }

        const rect = this.$el.getBoundingClientRect();

        this.dragging = true;
        this.moved = false;
        this.startX = event.clientX;
        this.startY = event.clientY;
        this.originX = rect.left;
        this.originY = rect.top;
        this.x = rect.left;
        this.y = rect.top;
        Alpine.store('mirza').dragging = true;

        this.moveListener = (moveEvent) => this.onMove(moveEvent);
        this.upListener = () => this.endDrag();

        window.addEventListener('pointermove', this.moveListener, { passive: false });
        window.addEventListener('pointerup', this.upListener);
        window.addEventListener('pointercancel', this.upListener);

        if (event.currentTarget?.setPointerCapture) {
            event.currentTarget.setPointerCapture(event.pointerId);
        }
    },
    onMove(event) {
        if (! this.dragging) {
            return;
        }

        const dx = event.clientX - this.startX;
        const dy = event.clientY - this.startY;

        if (Math.abs(dx) > 6 || Math.abs(dy) > 6) {
            this.moved = true;
        }

        this.x = this.originX + dx;
        this.y = this.originY + dy;
        this.clamp();

        if (event.cancelable) {
            event.preventDefault();
        }
    },
    endDrag() {
        if (! this.dragging) {
            return;
        }

        this.dragging = false;
        this.clamp();
        this.persist();

        window.setTimeout(() => {
            Alpine.store('mirza').dragging = false;
        }, 40);

        if (this.moveListener) {
            window.removeEventListener('pointermove', this.moveListener);
            this.moveListener = null;
        }

        if (this.upListener) {
            window.removeEventListener('pointerup', this.upListener);
            window.removeEventListener('pointercancel', this.upListener);
            this.upListener = null;
        }
    },
    minimize() {
        this.minimized = true;
        this.$nextTick(() => {
            this.clamp();
            this.persist();
        });
    },
    expand() {
        if (this.moved) {
            return;
        }

        this.minimized = false;
        this.$nextTick(() => {
            this.clamp();
            this.persist();
        });
    },
    destroy() {
        this.endDrag();
    },
}));

Alpine.data('workStage', (total = 1, interval = 8000) => ({
    index: 0,
    total,
    timer: null,
    locked: false,
    reduced: false,
    init() {
        this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (this.total > 1 && ! this.reduced) {
            this.play();
        }
    },
    play() {
        this.stop();
        this.locked = false;

        if (this.total < 2 || this.reduced) {
            return;
        }

        this.timer = window.setInterval(() => this.next(false), interval);
    },
    pause() {
        this.stop();
    },
    toggleLock() {
        if (this.locked) {
            this.play();
            return;
        }

        this.locked = true;
        this.stop();
    },
    onEnter() {
        if (! this.locked) {
            this.pause();
        }
    },
    onLeave() {
        if (! this.locked) {
            this.play();
        }
    },
    stop() {
        if (this.timer) {
            window.clearInterval(this.timer);
            this.timer = null;
        }
    },
    next(restart = true) {
        this.index = (this.index + 1) % this.total;

        if (restart) {
            this.play();
        }
    },
    prev() {
        this.index = (this.index - 1 + this.total) % this.total;
        this.play();
    },
    go(index) {
        this.index = index;
        this.play();
    },
    destroy() {
        this.stop();
    },
}));

Alpine.data('poseSection', () => ({
    init() {
        const pose = this.$el.getAttribute('data-character-pose');

        if (! pose) {
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    Alpine.store('mirza').setPose(pose);
                }
            });
        }, { threshold: 0.28 });

        observer.observe(this.$el);
    },
}));

Alpine.data('palette', () => ({
    query: '',
    index: 0,
    commands: [],
    init() {
        try {
            this.commands = JSON.parse(this.$el.dataset.commands || '[]');
        } catch (error) {
            this.commands = [];
        }

        this.$watch(
            () => Alpine.store('palette').open,
            (open) => {
                if (open) {
                    this.query = '';
                    this.index = 0;
                    this.$nextTick(() => this.$refs.search?.focus());
                }
            },
        );

        window.addEventListener('keydown', (event) => {
            if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                Alpine.store('palette').toggle();
            }

            if (event.key === 'Escape' && Alpine.store('palette').open) {
                Alpine.store('palette').close();
            }
        });
    },
    get filtered() {
        const query = this.query.trim().toLowerCase();

        if (! query) {
            return this.commands;
        }

        return this.commands.filter((command) => command.label.toLowerCase().includes(query));
    },
    move(delta) {
        const total = this.filtered.length;

        if (! total) {
            return;
        }

        this.index = (this.index + delta + total) % total;
    },
    run(command) {
        const item = command ?? this.filtered[this.index];

        if (! item) {
            return;
        }

        Alpine.store('palette').close();

        if (item.external) {
            window.open(item.url, '_blank', 'noopener');
            return;
        }

        window.location.href = item.url;
    },
}));

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initReveal();
});

function initReveal() {
    const nodes = document.querySelectorAll('.reveal');

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        nodes.forEach((node) => node.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

    nodes.forEach((node) => observer.observe(node));
}
