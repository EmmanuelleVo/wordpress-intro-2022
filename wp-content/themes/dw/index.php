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
                <article class="post">
                    <a href="<?= get_the_permalink() ?>" class="post__link">Lire l'article "<?= get_the_title() ?>"</a> <!-- position:absolute + z-index -->
                    <div class="post__card">
                        <header class="post__head">
                            <h3 class="post__title"><?= get_the_title() ?></h3>
                            <p class="post__meta">Publié par <?= get_the_author() ?>, le <time class="post__date" datetime="<?= get_the_date('c') ?>"><?= get_the_date() ?></time></p>
                        </header>
                        <figure class="post__fig">
                            <?= get_the_post_thumbnail(null, 'medium',['class' => 'post__thumb', 'id' => 'test']) ?>
                        </figure>
                        <div class="post__excerpt">
                            <p><?= get_the_excerpt() ?></p>
                        </div>
                    </div>
                </article>
            <?php endwhile; else : ?>
                <p>No</p>
            <?php endif; ?>
    </div>
    </section>
</main>

<?php get_footer() ?>