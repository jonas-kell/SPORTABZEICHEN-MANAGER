/**
 * replace spaces with non-breaking spaces
 */
export function nbsp_encode(string: string) {
  return string.replace(' ', '\xa0');
}

/**
 * replace spaces with non-breaking spaces
 */
export function build_tooltip(requirement: string, description = '') {
  if (description == undefined || description == '') {
    return requirement;
  } else {
    return requirement + ': ' + description;
  }
}
