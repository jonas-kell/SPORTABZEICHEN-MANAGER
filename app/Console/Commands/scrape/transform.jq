def group_to_name:
  if . == 1 then "endurance"
  elif . == 2 then "strength"
  elif . == 3 then "speed"
  elif . == 4 then "coordination"
  else "unknown"
  end;

.[] 
| .content[]
| . as $item

| $item.performance_conditions[]
| .data as $pc

| ($pc.age_group_id | tonumber) as $age_id
| ($pc.group_id | group_to_name) as $group

| {
    label: $item.label,
    age_group: ($ages[0] | .[$age_id - 1].lower_year),
    category: $group
  }