export function nextEnum<T>(anEnum: T, aValue: T[keyof T]): T[keyof T] {
  const enumValues = Object.values(anEnum) as unknown as T[keyof T][];
  const nextIndex = enumValues.findIndex(value => value === aValue) + 1;

  return enumValues[nextIndex === enumValues.length ? 0 : nextIndex];
}
