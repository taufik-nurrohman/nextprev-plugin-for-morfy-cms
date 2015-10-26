<ul class="{$config.classes.nav}">
  {if $prev}
    <li class="{$config.classes.nav_prev}">
      <a href="{$prev}">{$config.labels.nav_prev}</a>
    </li>
  {else}
    <li class="{$config.classes.nav_prev} {$config.classes.nav_disabled}">
      <span>{$config.labels.nav_prev}</span>
    </li>
  {/if}
  {if $next}
    <li class="{$config.classes.nav_next}">
      <a href="{$next}">{$config.labels.nav_next}</a>
    </li>
  {else}
    <li class="{$config.classes.nav_next} {$config.classes.nav_disabled}">
      <span>{$config.labels.nav_next}</span>
    </li>
  {/if}
</ul>