<?php get_header() ?>

    <main class="layout">
        <section class="layout__intro">
            <h2 class="layout__title">Introduction</h2>
            <p class="layout__text">Welcome&nbsp;!</p>
        </section>

        <section class="layout__latest latest">
            <h2 class="latest__title">Nos dernières nouvelles</h2>
            <div class="latest__container">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php dw_include('post', ['modifier' => 'index']); ?>
				<?php endwhile; else : ?>
                    <p>No</p>
				<?php endif; ?>
            </div>
        </section>

        <section class="layout__trips trips">
            <h2 class="trips__title">Mes derniers voyages</h2>
            <div class="trips__container">
                <?php
                $trips = dw_get_trips(3);
                if (($trips/* = dw_get_trips(3)*/)->have_posts() ) : while ($trips->have_posts() ) : $trips->the_post(); ?>
	                <?php dw_include('trip', ['modifier' => 'index']); ?>
				<?php endwhile; else : ?>
                    <p class="trips__empty">Il n'y a pas encore de voyage</p>
				<?php endif; ?>
            </div>
        </section>

    </main>

<?php get_footer() ?>